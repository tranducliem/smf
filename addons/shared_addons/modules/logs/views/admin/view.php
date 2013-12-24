<section class="title">
    <h4><?php echo lang('global:' . $this->method) . ' ' . $filename; ?> </h4>
</section>

<section class="item">
    <fieldset>
        <pre class="scrollable">
            <code data-language="log">
                <?php echo htmlspecialchars(strip_tags($content)); ?>
            </code>
        </pre>
        <div class="buttons">
            <?php $this->load->view('admin/partials/buttons', array('buttons' => array('cancel'))); ?>
        </div>
    </fieldset>
</section>