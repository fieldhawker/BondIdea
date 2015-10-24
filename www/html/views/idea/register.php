<?php $this->setLayoutVar('title', 'キーワード') ?>

<div class="row">
    
    <h5 class="well well-lg text-center">アイデアのベースとなるキーワードを入力してください。</h5>
    
    <form method="post" class="form-signin" action="/idea/register">


        <label for="inputEmail" class="sr-only">キーワード</label>
        <input type="text" id="keyword" class="form-control" value=""
               name="keyword" placeholder="Keyword" required autofocus>
        <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>"/>

        <button class="btn btn-lg btn-primary btn-block" type="submit">組み合わせる</button>

        <?php if (isset($errors) && count($errors) > 0): ?>
            <?php echo $this->render('errors', array('errors' => $errors)); ?>
        <?php endif; ?>

    </form>
</div>

<?php if (isset($modifiers) && count($modifiers) > 0): ?>
<div class="row" style="padding:30px 0 0 0">
    <div class="well well-lg text-center">
            <?php foreach ($modifiers as $modifier): ?>
        <h3>
                <?php echo $keyword; ?>に<?php echo $modifier["keyword"]; ?>を組み合わせたら？<br />
        </h3>
            <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

