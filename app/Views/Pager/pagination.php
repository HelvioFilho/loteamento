<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>

<nav aria-label="Page navigation">
  <ul class="pagination pagination-lg justify-content-center">
    <?php if ($pager->hasPrevious()) : ?>
      <li class="page-item">
        <a href="<?= $pager->getPrevious() ?>" class="page-link" aria-label="Previous">
          <span aria-hidden="true">&laquo;</span>
        </a>
      </li>

    <?php endif ?>

    <?php foreach ($pager->links() as $link) : ?>
      <li <?= $link['active'] ? 'class="page-item active"' : 'page-item' ?>>
        <a class="page-link" href="<?= $link['uri'] ?>">
          <?= $link['title'] ?>
        </a>
      </li>
    <?php endforeach ?>

    <?php if ($pager->hasNext()) : ?>
      <li class="page-item">
        <a class="page-link" href="<?= $pager->getNext() ?>" aria-label="Next">
          <span aria-hidden="true">&raquo;</span>
        </a>
      </li>
    <?php endif ?>
  </ul>
</nav>