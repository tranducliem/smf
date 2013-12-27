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
        <h4>Date: </h4> <?php echo $post->date;?>
        <h4>Apply user: </h4> <?php
        if($apply)  echo $apply->username;
        ?>
        <h4>Department: </h4> <?php if($department) echo $department->title;?>
        <h4>Status: </h4>
        <?php
        if($post->status == 0) echo "Not start";
        else if($post->status == 1) echo "Processing";
        else echo "Done";
        ?>
    </div>
</div>


