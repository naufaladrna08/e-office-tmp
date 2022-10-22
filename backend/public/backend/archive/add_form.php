<style type="text/css">
    #elm-form .control-group {
        margin-bottom: 0px;
    }
    #elm-form label.control-label {
        font-weight: bold;
    }
</style>
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'id'=>'elm-form',
    'enableAjaxValidation'=>false,
    'htmlOptions'=>array(
        'autocomplete'=>'off',
        'enctype' => 'multipart/form-data'
    )
)); ?>
<input type="hidden" name="FORM_ID" value="<?= $form_id ?>" id="">
<div class="row-fluid" style="margin-bottom: 10px;">
    <div class="span10">
        <fieldset>
            <?php echo implode('', $elm); ?>
        </fieldset>
    </div>
    <div class="span2"><button onclick="addSection()" type="button" class="btn btn-info btn-block btn-small">Add New</button></div>
</div>

<div id="additional-form">
    
</div>
<div id="elm-form-content"></div>
<div class="form-actions">
    <button type="button" onclick="submitForm()" class="btn btn-primary">Save changes</button>
    <button type="button" class="btn btn-main" data-dismiss="modal" aria-label="Close">Cancel</button>
</div>
<?php $this->endWidget(); ?>

<script id="section-template" type="text/dust">
    <div id="form_section_{section_index}" class="row-fluid" style="margin-bottom: 10px;">
        <div class="span11">
            <fieldset><?php echo htmlspecialchars_decode(implode('', $elm_copy)); ?></fieldset>
        </div>
        <div class="span1"><button onclick="removeSection({section_index})" type="button" class="btn btn-danger btn-block">-</button></div>
    </div>
</script>

<script type="text/javascript">
    dateSelector( '.dateInput');

    var data = { 
        section_index: 0
    }

    function addSection() {
        data.section_index++
        generateDustPage(data, true, 'section-template', '#additional-form')
    }

    function removeSection(section_index) {
        $('#form_section_'+section_index).remove()
    }

    
</script>