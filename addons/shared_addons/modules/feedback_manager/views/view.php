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
    <h4>Start date: </h4> <?php echo $post->start_date;?>
    <h4>End date: </h4> <?php echo $post->end_date;?>
    <h4>Type_id: </h4> <?php echo $type->title;?>
    <h4>Require: </h4> <?php
        if($post->require == 0) echo "Not require";
        else echo "Require";
    ?>
    <h4>Status: </h4>
    <?php
        if($post->status == 0) echo "Not start";
        else if($post->status == 1) echo "Processing";
        else echo "Done";
    ?>
</div>

