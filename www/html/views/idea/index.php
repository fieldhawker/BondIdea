<?php $this->setLayoutVar('title', 'キーワード') ?>

<div class="row">

    <h5 class="well text-center">アイデアのベースとなるキーワードを入力してください。</h5>
    
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