
<input type="hidden" name="webToken" value="56">
<input type="hidden" name="request" value="participant-editSessions">
<input type="hidden" name="participant-event_id" value="<?=$event_ID?>">
<input type="hidden" name="participant-company_id" value="<?=$company_data->ID?>">
<input type="hidden" name="participant-id" value="<?=$participant_data->ID?>">
<div class="form-group">
    <label for="gala_dinner">Gala dinner</label>
    <select name="participant-gala_dinner" id="gala_dinner" class="form-control bordered" required="required">
       
        <option value="" <?php if($participant_data->gala_dinner == ''){ echo 'selected="selected"';}?>> [--Select--] </option>


            <option value="Confirm" <?php if($participant_data->gala_dinner == 'Confirm'){ echo 'selected="selected"';}?>>Confirm</option>

            <option value="" <?php if($participant_data->gala_dinner == ''){ echo 'selected="selected"';}?>>Pending</option>
        
            <option value="Deny" <?php if($participant_data->gala_dinner == 'Deny'){ echo 'selected="selected"';}?>>Deny</option>
        
    </select>
</div>
<div class="form-group">
    <label for="board_meeting">Board meeting</label>
    <select name="participant-board_meeting" id="board_meeting" class="form-control bordered" required="required">
       
        <option value="" <?php if($participant_data->board_meeting == ''){ echo 'selected="selected"';}?>> [--Select--] </option>


            <option value="Confirm" <?php if($participant_data->board_meeting == 'Confirm'){ echo 'selected="selected"';}?>>Confirm</option>

            <option value="" <?php if($participant_data->board_meeting == ''){ echo 'selected="selected"';}?>>Pending</option>
        
            <option value="Deny" <?php if($participant_data->board_meeting == 'Deny'){ echo 'selected="selected"';}?>>Deny</option>
        
    </select>
</div>
<div class="form-group">
    <label for="smart_women">Smart women's lunch</label>
    <select name="participant-smart_women" id="smart_women" class="form-control bordered" required="required">
       
        <option value="" <?php if($participant_data->smart_women == ''){ echo 'selected="selected"';}?>> [--Select--] </option>


            <option value="Confirm" <?php if($participant_data->smart_women == 'Confirm'){ echo 'selected="selected"';}?>>Confirm</option>

            <option value="" <?php if($participant_data->smart_women == ''){ echo 'selected="selected"';}?>>Pending</option>
        
            <option value="Deny" <?php if($participant_data->smart_women == 'Deny'){ echo 'selected="selected"';}?>>Deny</option>
        
    </select>
</div>
<div class="form-group">
    <label for="mayor_lunch">Mayor's lunch</label>
    <select name="participant-mayors_lunch" id="mayors_lunch" class="form-control bordered" required="required">
       
        <option value="" <?php if($participant_data->mayors_lunch == ''){ echo 'selected="selected"';}?>> [--Select--] </option>


            <option value="Confirm" <?php if($participant_data->mayors_lunch == 'Confirm'){ echo 'selected="selected"';}?>>Confirm</option>

            <option value="" <?php if($participant_data->mayors_lunch == ''){ echo 'selected="selected"';}?>>Pending</option>
        
            <option value="Deny" <?php if($participant_data->mayors_lunch == 'Deny'){ echo 'selected="selected"';}?>>Deny</option>
        
    </select>
</div>
<div class="form-group">
    <label for="ceo_lunch">CEO lunch</label>
    <select name="participant-ceo_lunch" id="ceo_lunch" class="form-control bordered" required="required">
       
        <option value="" <?php if($participant_data->ceo_lunch == ''){ echo 'selected="selected"';}?>> [--Select--] </option>


            <option value="Confirm" <?php if($participant_data->ceo_lunch == 'Confirm'){ echo 'selected="selected"';}?>>Confirm</option>

            <option value="" <?php if($participant_data->ceo_lunch == ''){ echo 'selected="selected"';}?>>Pending</option>
        
            <option value="Deny" <?php if($participant_data->ceo_lunch == 'Deny'){ echo 'selected="selected"';}?>>Deny</option>
        
    </select>
</div>