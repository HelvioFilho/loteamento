<?php

namespace App\Controllers;

use App\Models\PaymentListModel;
use App\Models\PlotsModel;
use App\Models\UserPaymentModel;
use App\Models\UsersModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PaymentList extends BaseController
{
    public function index()
    {
        // Carrega o modelo da lista de pagamentos e dos usuários
        $paymentListModel = new PaymentListModel();

        $search = $this->request->getGet('search') ?? '';

        if ($search) {
            // Verificar se a busca está no formato mês/ano
            if (preg_match('/^\d{1,2}\/\d{4}$/', $search)) {
                // Caso esteja no formato mês/ano (ex: 12/2024)
                list($month, $year) = explode('/', $search);
                $month = intval($month); // Garantir que seja um número inteiro

                // Buscar no banco de dados pelos campos de mês e ano
                $paymentListModel->groupStart()
                    ->where('month', $month)
                    ->where('year', $year)
                    ->groupEnd();
            } else {
                // Caso não esteja no formato de data, buscar pelos outros campos
                $paymentListModel->groupStart()
                    ->orLike('name', $search)
                    ->orLike('month', $search)
                    ->orLike('year', $search)
                    ->groupEnd();
            }
        }

        // Busca todas as listas de pagamentos existentes no banco de dados
        $lists = $paymentListModel->orderBy('created_at', 'DESC')->paginate(10);


        $headerData = [
            'title' => 'Gerenciamento de Listas de Pagamento',
            'lists' => $lists,
            'pager' => $paymentListModel->pager,
            'search' => $search
        ];

        // Carrega a visão passando as listas e os usuários
        return view('paymentList', $headerData);
    }

    public function createList()
    {
        $paymentListModel = new PaymentListModel();
        $name = $this->request->getPost('name');
        $month = $this->request->getPost('month');
        $year = $this->request->getPost('year');

        $paymentListModel->insert([
            'name' => $name,
            'month' => $month,
            'year' => $year,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/lista-de-pagamentos')->with('message', 'Lista de pagamentos criada com sucesso.'); // Redirecionar para a página de listas
    }

    public function savePayments()
    {
        $userPaymentModel = new UserPaymentModel();

        // Obtém os dados enviados via JSON
        $json = $this->request->getJSON();

        $paymentListId = $json->payment_list_id;
        $payments = $json->payments;

        foreach ($payments as $payment) {
            $data = [
                'user_id' => $payment->user_id,
                'payment_list_id' => $paymentListId,
                'paid' => $payment->paid
            ];

            // Atualiza ou insere pagamento
            $existing = $userPaymentModel->where('user_id', $payment->user_id)
                ->where('payment_list_id', $paymentListId)
                ->first();

            if ($existing) {
                $userPaymentModel->update($existing->id, $data);
            } else {
                $userPaymentModel->insert($data);
            }
        }

        return $this->response->setJSON(['success' => true]);
    }

    public function editList()
    {
        // Carrega o modelo da lista de pagamentos
        $paymentListModel = new PaymentListModel();

        // Obtém os dados do POST
        $listId = $this->request->getPost('list_id');
        $newName = $this->request->getPost('name');
        $newMonth = $this->request->getPost('month');
        $newYear = $this->request->getPost('year');

        // Verificar se a lista existe
        $list = $paymentListModel->find($listId);
        if (!$list) {
            return $this->response->setJSON(['success' => false, 'message' => 'Lista não encontrada.']);
        }

        // Atualizar os dados da lista
        $data = [
            'name' => $newName,
            'month' => $newMonth,
            'year' => $newYear
        ];

        if ($paymentListModel->update($listId, $data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Lista atualizada com sucesso.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Erro ao atualizar a lista.']);
        }
    }

    public function manage($listId)
    {
        // Carrega o modelo da lista de pagamentos e do pagamento dos usuários
        $paymentListModel = new PaymentListModel();
        $userPaymentModel = new UserPaymentModel();
        $usersModel = new UsersModel();
        $plotsModel = new PlotsModel();

        // Busca a lista de pagamentos específica pelo ID
        $list = $paymentListModel->find($listId);

        if (!$list) {
            // Se a lista não for encontrada, redireciona de volta para a página de listas de pagamento
            return redirect()->to('/payments')->with('message', 'Lista de pagamentos não encontrada.');
        }

        // Busca todos os usuários do sistema
        $allUsers = $usersModel
            ->orderBy('name', 'ASC')
            ->findAll();

        foreach ($allUsers as $user) {
            // Buscar somente os dados necessários do plot (ex: plot_number, side)
            $plots = $plotsModel
                ->select('plot_number, side')
                ->where('user_id', $user->id)
                ->findAll();

            // 3. Anexar em uma propriedade do objeto user
            $user->plots = $plots;
        }

        // Busca os pagamentos existentes para a lista atual
        $userPayments = $userPaymentModel->where('payment_list_id', $listId)->findAll();

        // Cria um array associativo de pagamentos para facilitar a verificação
        $userPaymentsMap = [];
        foreach ($userPayments as $userPayment) {
            $userPaymentsMap[$userPayment->user_id] = $userPayment->paid;
        }

        // Junta os detalhes do usuário com as informações de pagamento
        $users = [];
        foreach ($allUsers as $user) {
            // Verificar se o usuário já possui um registro de pagamento
            $user->paid = $userPaymentsMap[$user->id] ?? false;

            $leftSidePlots = [];
            $rightSidePlots = [];

            foreach ($user->plots as $plot) {
                if (strtolower($plot->side) === 'esquerdo') {
                    $leftSidePlots[] = $plot->plot_number;
                } elseif (strtolower($plot->side) === 'direito') {
                    $rightSidePlots[] = $plot->plot_number;
                }
            }

            $leftStr = implode(', ', $leftSidePlots);
            $rightStr = implode(', ', $rightSidePlots);

            if ($leftStr && $rightStr) {
                // Se ambos existem
                $plotsString = $leftStr . ' esquerda - ' . $rightStr . ' direita';
            } elseif ($leftStr) {
                // Só tem esquerda
                $plotsString = $leftStr . ' esquerda';
            } elseif ($rightStr) {
                // Só tem direita
                $plotsString = $rightStr . ' direita';
            } else {
                // Não tem lote de nenhum lado (ou array vazio)
                $plotsString = '';
            }

            $user->plots = $plotsString;

            $users[] = $user;
        }

        // Carrega a visão de gerenciamento de pagamentos
        return view('paymentManageList', [
            'list' => $list,
            'users' => $users,
            'title' => 'Gerenciar Pagamentos - ' . esc($list->name)
        ]);
    }

    public function deleteList($listId)
    {
        // Carrega o modelo da lista de pagamentos e dos pagamentos dos usuários
        $paymentListModel = new PaymentListModel();
        $userPaymentModel = new UserPaymentModel();

        // Verifica se a lista de pagamento existe
        $list = $paymentListModel->find($listId);

        if (!$list) {
            return redirect()->to('/lista-de-pagamentos')->with('error', 'Lista de pagamentos não encontrada.');
        }

        // Exclui todos os registros de pagamentos associados à lista
        $userPaymentModel->where('payment_list_id', $listId)->delete();

        // Exclui a lista de pagamentos
        $paymentListModel->delete($listId);

        return redirect()->to('/lista-de-pagamentos')->with('success', 'Lista de pagamentos excluída com sucesso.');
    }

    public function exportExcel($listId)
    {
        // Carrega o modelo da lista de pagamentos e dos usuários
        $paymentListModel = new PaymentListModel();
        $userPaymentModel = new UserPaymentModel();
        $usersModel = new UsersModel();
        $plotsModel = new PlotsModel();

        // Busca a lista de pagamentos específica pelo ID
        $list = $paymentListModel->find($listId);

        if (!$list) {
            return redirect()->to('/payments')->with('message', 'Lista de pagamentos não encontrada.');
        }

        // Busca todos os usuários e seus status de pagamento
        $allUsers = $usersModel->orderBy('name', 'ASC')->findAll();
        $userPayments = $userPaymentModel->where('payment_list_id', $listId)->findAll();

        $userPaymentsMap = [];
        foreach ($userPayments as $userPayment) {
            $userPaymentsMap[$userPayment->user_id] = $userPayment->paid;
        }

        // Cria a planilha
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Define o cabeçalho
        $sheet->setCellValue('A1', 'Nome');
        $sheet->setCellValue('B1', 'Lotes');
        $sheet->setCellValue('C1', 'Pago');

        // Arrays de estilo para “pago” e “não pago”
        $stylePaid = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '39FF14'], // verde claro
            ],
        ];
        $styleNotPaid = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'FF0000'], // vermelho claro
            ],
        ];

        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'], // Cor da fonte
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '4F81BD'], // Azul, por exemplo
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ],
        ];

        $sheet->getStyle('A1:C1')->applyFromArray($headerStyle);

        // Preenche os dados dos usuários
        $row = 2; // Começa na linha 2 porque a linha 1 é o cabeçalho
        foreach ($allUsers as $user) {
            // Verifica se está pago (sim ou não)
            $isPaid = isset($userPaymentsMap[$user->id]) && $userPaymentsMap[$user->id] ? 'Sim' : 'Não';

            // Busca plots do usuário
            $plots = $plotsModel->select('plot_number, side')->where('user_id', $user->id)->findAll();

            // Agrupa lotes em arrays de esquerda/direita
            $leftSidePlots  = [];
            $rightSidePlots = [];

            foreach ($plots as $plot) {
                $sideLower = strtolower($plot->side);
                if ($sideLower === 'esquerdo') {
                    $leftSidePlots[] = $plot->plot_number;
                } elseif ($sideLower === 'direito') {
                    $rightSidePlots[] = $plot->plot_number;
                }
            }

            // Monta a string final de lotes
            $leftStr  = implode(', ', $leftSidePlots);
            $rightStr = implode(', ', $rightSidePlots);

            if ($leftStr && $rightStr) {
                // Ambos os lados
                $lotsString = $leftStr . ' esquerdo - ' . $rightStr . ' direito';
            } elseif ($leftStr) {
                // Só esquerda
                $lotsString = $leftStr . ' esquerdo';
            } elseif ($rightStr) {
                // Só direita
                $lotsString = $rightStr . ' direito';
            } else {
                // Sem lotes
                $lotsString = '';
            }

            // Preenche na planilha
            $sheet->setCellValue('A' . $row, $user->name);
            $sheet->setCellValue('B' . $row, $lotsString);
            $sheet->setCellValue('C' . $row, $isPaid);

            // Aplica o estilo à linha inteira (A..C) ou às colunas que quiser
            if ($isPaid === 'Sim') {
                $sheet->getStyle("A{$row}:C{$row}")->applyFromArray($stylePaid);
            } else {
                $sheet->getStyle("A{$row}:C{$row}")->applyFromArray($styleNotPaid);
            }

            $row++;
        }

        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Define o nome do arquivo
        $filename = 'pagamentos_' . $list->name . '_' . $list->month . '_' . $list->year . '.xlsx';

        // Prepara o response para download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
