<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Zrenderer &mdash; Health</h2>
<?php if (isset($_POST['submit'])): ?>
<?php if ($error): ?>
<p>
    <strong>An error occurred:</strong> <?php echo $errorMessage ?>
</p>
<?php else: ?>
<table class="vertical-table">
    <tbody>
        <tr>
            <th scope="row">Status</th>
            <td><?php echo ($health->up ? 'Up' : 'Down') ?></td>
        </tr>
        <?php if (isset($health->gc)): ?>
        <tr>
            <th scope="row">GC</th>
            <td>Free size: <?php echo $health->gc->freeSize ?> bytes<br/>Used size: <?php echo $health->gc->usedSize ?> bytes</td>
        </tr>
        <?php endif ?>
    </tbody>
</table>
<?php endif?>
<?php endif ?>
<p>
    <form method="post" action="<?php echo $this->url('renderplayer', 'health') ?>">
        <input type="submit" name="submit" onclick="this.value='Getting health...';" class="button" value="Check health"/>
    </form>
</p>
