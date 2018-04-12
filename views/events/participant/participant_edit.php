<style>
    .cropit-preview {
        background-color: #f8f8f8;
        background-size: cover;
        border: 1px solid #ccc;
        border-radius: 3px;
        margin-top: 7px;
        width: 200px;
        height: 200px;
    }

    .cropit-preview-image-container {
        cursor: move;
    }
</style><script type="text/javascript">
   function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
        $('#test').attr('src', e.target.result);
       }
        reader.readAsDataURL(input.files[0]);
       }
    }
</script>
  <input type="hidden" name="webToken" value="56">
  <input type="hidden" name="request" value="participant-editCategory">
  <input type="hidden" name="participant-event_id" value="<?=$event_ID?>">
  <input type="hidden" name="participant-company_id" value="<?=$company_data->ID?>">
  <input type="hidden" name="participant-id" value="<?=$participant_data->ID?>">
  <div class="form-group">
    <label for="category">Category</label>
    <select name="participant-category" id="category" class="form-control bordered" required="required">
       
        <option value="" <?php if($participant_data->category == ''){ echo 'selected="selected"';}?>> [--Select--] </option>


        
            <option value="Delegate" <?php if($participant_data->category == 'Delegate'){ echo 'selected="selected"';}?>>Delegate</option>
        
            <option value="Government" <?php if($participant_data->category == 'Government'){ echo 'selected="selected"';}?>>Government</option>

            <option value="Media" <?php if($participant_data->category == 'Media'){ echo 'selected="selected"';}?>>Media</option>
        
            <option value="NOC" <?php if($participant_data->category == 'NOC'){ echo 'selected="selected"';}?>>NOC</option>
        
            <option value="Organiser" <?php if($participant_data->category == 'Organiser'){ echo 'selected="selected"';}?>>Organiser</option>
        
            <option value="Delegate-Liaison" <?php if($participant_data->category == 'Delegate-Liaison'){ echo 'selected="selected"';}?>>Delegate-Liaison</option>
        
            <option value="Armed-Security" <?php if($participant_data->category == 'Armed-Security'){ echo 'selected="selected"';}?>>Armed-Security</option>
        
            <option value="Security" <?php if($participant_data->category == 'Security'){ echo 'selected="selected"';}?>>Security</option>
        
            <option value="Protocol" <?php if($participant_data->category == 'Protocol'){ echo 'selected="selected"';}?>>Protocol</option>
        
            <option value="Medical" <?php if($participant_data->category == 'Medical'){ echo 'selected="selected"';}?>>Medical</option>
        
            <option value="Catering" <?php if($participant_data->category == 'Catering'){ echo 'selected="selected"';}?>>Catering</option>
        
            <option value="Production-Technician" <?php if($participant_data->category == 'Production-Technician'){ echo 'selected="selected"';}?>>Production-Technician</option>
        
            <option value="Contractor" <?php if($participant_data->category == 'Contractor'){ echo 'selected="selected"';}?>>Contractor</option>
        
            <option value="Delegate invited" <?php if($participant_data->category == 'Delegate invited'){ echo 'selected="selected"';}?>>Delegate invited</option>
            
            <option value="Delegate request" <?php if($participant_data->category == 'Delegate request'){ echo 'selected="selected"';}?>>Delegate request</option>

            <option value="Government invited" <?php if($participant_data->category == 'Government invited'){ echo 'selected="selected"';}?>>Government invited</option>

            <option value="Government request" <?php if($participant_data->category == 'Government request'){ echo 'selected="selected"';}?>>Government request</option>

            <option value="Media request" <?php if($participant_data->category == 'Media request'){ echo 'selected="selected"';}?>>Media request</option>


    </select>
  </div>

  <div class="form-group">
    <label for="pass">Pass</label>
    <select name="participant-pass" id="pass" class="form-control bordered" required="required">
       
        <option value="" <?php if($participant_data->pass_type == ''){ echo 'selected="selected"';}?>> [--Select--] </option>


        
            <option value="VVIP" <?php if($participant_data->pass_type == 'VVIP'){ echo 'selected="selected"';}?>>VVIP</option>
        
            <option value="VIP" <?php if($participant_data->pass_type == 'VIP'){ echo 'selected="selected"';}?>>VIP</option>

            <option value="Delegate" <?php if($participant_data->pass_type == 'Delegate'){ echo 'selected="selected"';}?>>Delegate</option>
        
            <option value="Government" <?php if($participant_data->pass_type == 'Government'){ echo 'selected="selected"';}?>>Government</option>

            <option value="Media" <?php if($participant_data->pass_type == 'Media'){ echo 'selected="selected"';}?>>Media</option>
        
            <option value="NOC" <?php if($participant_data->pass_type == 'NOC'){ echo 'selected="selected"';}?>>NOC</option>
        
            <option value="Organiser" <?php if($participant_data->pass_type == 'Organiser'){ echo 'selected="selected"';}?>>Organiser</option>
        
            <option value="Delegate-Liaison" <?php if($participant_data->pass_type == 'Delegate-Liaison'){ echo 'selected="selected"';}?>>Delegate-Liaison</option>
        
            <option value="Armed-Security" <?php if($participant_data->pass_type == 'Armed-Security'){ echo 'selected="selected"';}?>>Armed-Security</option>
        
            <option value="Security" <?php if($participant_data->pass_type == 'Security'){ echo 'selected="selected"';}?>>Security</option>
        
            <option value="Protocol" <?php if($participant_data->pass_type == 'Protocol'){ echo 'selected="selected"';}?>>Protocol</option>
        
            <option value="Medical" <?php if($participant_data->pass_type == 'Medical'){ echo 'selected="selected"';}?>>Medical</option>
        
            <option value="Catering" <?php if($participant_data->pass_type == 'Catering'){ echo 'selected="selected"';}?>>Catering</option>
        
            <option value="Production-Technician" <?php if($participant_data->pass_type == 'Production-Technician'){ echo 'selected="selected"';}?>>Production-Technician</option>
        
            <option value="Contractor" <?php if($participant_data->pass_type == 'Contractor'){ echo 'selected="selected"';}?>>Contractor</option>
        
            <option value="Moderator" <?php if($participant_data->pass_type == 'Moderator'){ echo 'selected="selected"';}?>>Moderator</option>
            
            <option value="COMS" <?php if($participant_data->pass_type == 'COMS'){ echo 'selected="selected"';}?>>COMS</option>

            <option value="Speaker" <?php if($participant_data->pass_type == 'Speaker'){ echo 'selected="selected"';}?>>Speaker</option>

            <!-- <option value="Government request" <?php if($participant_data->pass_type == 'Government request'){ echo 'selected="selected"';}?>>Government request</option> -->



    </select>
  </div>

  <div class="form-group">
    <label for="pass">Photo</label>
    <div class="image-editor">
        <div class="row">
            <div class="col-sm-4" style="padding: 5px">
                <div class="form-group">
                    <input name="register-photo" onchange="readURL(this);" type="file" />
                </div>
            </div>
            <div class="col-sm-4" style="padding: 5px">

                <img alt="Image Display Here" class="cropit-preview" id="test" src="<?=DNADMIN?>/icon/photo.png" />
            </div>
        </div>
    </div>

  </div>



