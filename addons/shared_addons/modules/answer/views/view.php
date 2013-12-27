<div class="span9">
    <style>
        h4{color: palevioletred}
    </style>
    <div class="blog margin-bottom-30">
        <h3><?php echo $post->title;?></h3>
        <ul class="unstyled inline blog-info">
            <li>
                <i class="icon-calendar"></i>
                <?php echo $post->created;?>
            </li>
            <li>
                <i class="icon-pencil"></i>
                <?php echo $user->username;?>
            </li>
        </ul>
        <ul class="unstyled inline blog-tags"></ul>
        <div class="blog-img"></div>
        <h4>Desciption:</h4>
        <p><?php echo $post->description;?></p>
        <h4>Question: </h4> <?php echo $question->title;?>
    </div>
</div>


