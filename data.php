<?php
    //use App\Post;

function insert_data_fn()
{

    global $wpdb;
    $request_id     = $_POST['reqid'];
    $meeting_id    = $_POST['meet_id']; //string value use: %s
    $room_name    = 'sb-tw-'.uniqid();
    $meeting_time      = $_POST['meet_time']; //string value use: %s
    $meeting_date   = $_POST['meet_date']; //string value use: %s
    $tutuor_id  = $_POST['tut_id']; //string value use: %s
    $student_id = $_POST['stu_id']; //string value use: %s
    //$meeting_link = $_POST['meeting_link'];
    $table_name = $wpdb->prefix . "sb_video_app_details";
    $wpdb->insert($table_name, array(
        "request_id" => $request_id,
        "meeting_id" => $meeting_id,
        "room_name" => $room_name,
        "meeting_time" => $meeting_time,
        "meeting_date" => $meeting_date,
        "tutuor_id" => $tutuor_id,
        "student_id" => $student_id,
    ));
?>
    <div class="wrapper" style="padding: 20px;">
        <form action="" method="post">
            <div class="row">
                <div class="col-md-6">
                    <label>Request Id</label>
                    <input type="text" name="reqid" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Meeting Id</label>
                    <input type="text" name="meet_id" class="form-control">
                </div>
                
                <div class="col-md-6">
                    <label>Meeting Time</label>
                    <input type="time" name="meet_time" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Meeting Date</label>
                    <input type="date" name="meet_date" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Tutour Id</label>
                    <input type="text" name="tut_id" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Student Id</label>
                    <input type="text" name="stu_id" class="form-control">
                </div>
                
                <div class="col-md-6">
                    <input type="hidden" name="action" value="data_form">
                    <input type="submit" class="btn btn-md btn-info" name="submit" value="Insert">
                </div>
            </div>
        </form>
    </div>
    <div class="wrapper" style="padding: 20px; overflow-x: scroll;">
        <table class="table-bordered table-responsive">
            <thead>
                <th>request_id</th>
                <th>meeting_id</th>
                <th>room_name</th>
                <th>meeting_time</th>
                <th>meeting_date</th>
                <th>tutuor_id</th>
                <th>student_id</th>
                <th>tutor_token</th>
                <th>student_token</th>
                <th>room_sid</th>
                <th>tutor_join_status</th>
                <th>student_join_status</th>
                <th>remarks</th>
            </thead>
            <tbody>
                <?php $tablename = $wpdb->prefix . "sb_video_app_details";
                $results = $wpdb->get_results("SELECT * FROM $tablename");
                if (!empty($results)) {
                    // echo '<pre>';
                    // print_r($results);
                    foreach ($results as $row) { ?>
                        <tr>
                            <td><?php echo $row->request_id; ?></td>
                            <td><?php echo $row->meeting_id; ?></td>
                            <td><?php echo $row->room_name; ?></td>
                            <td><?php echo $row->meeting_time; ?></td>
                            <td><?php echo $row->meeting_date; ?></td>
                            <td><?php echo $row->tutuor_id; ?></td>
                            <td><?php echo $row->student_id; ?></td>
                            <td><?php echo $row->tutor_token; ?></td>
                            <td><?php echo $row->student_token; ?></td>
                            <td><?php echo $row->room_sid; ?></td>
                            <td><?php echo $row->tutor_join_status; ?></td>
                            <td><?php echo $row->student_join_status; ?></td>
                            <td><?php echo $row->remarks; ?></td>
                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
<!-- stopwatch -->

<?php

}
add_shortcode('insert_data', 'insert_data_fn');

add_action( 'after_booking_insert', 'insert_session_data', 1, 2 );
function insert_session_data($booking_id, $booking){
    global $wpdb;
    $table_name = $wpdb->prefix . "sb_video_app_details";
    $wpdb->insert($table_name, array(
        "request_id" => $booking['request_id'],
        "meeting_id" => 'FG'.uniqid().$booking['request_id'],
        "room_name" => 'sb-tw-'.uniqid().$booking['request_id'],
        "meeting_time" => '',
        "meeting_date" => $booking['start_str'],
        "tutuor_id" => get_current_user_id(),
        "student_id" => $booking['student_id'],
    ));
    do_action('after_meeting_insert', $booking_id, $booking);
}


add_shortcode('sb_show_table', 'show_table_fn');
function show_table_fn(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'sb_video_app_details';
    // $prepared_statement = $wpdb->prepare( "SELECT * FROM $table_name ");
    // $values = $wpdb->get_col( $prepared_statement );
    $existing_columns = $wpdb->get_col("DESC {$table_name}", 0);
    $results = $wpdb->get_results("SELECT * FROM $table_name");

    // Implode to a string suitable for inserting into the SQL query
    $sql = implode( ', ', $existing_columns );

    var_dump($sql);

    $i = 0;
    ob_start();
    echo "<table>";
    foreach($results as $values){
        if($i == 0){
            echo "<tr>";
            echo "<th>id</th>";
            echo "<th>request_id</th>";
            echo "<th>tutor_join_status</th>";
            echo "<th>student_join_status</th>";
            echo "<th>remarks</th>";
            echo "</tr>";
        }

        echo "<tr>";
        echo "<td>".$values->id."</td>";
        echo "<td>".$values->request_id."</td>";
        echo "<td>".$values->tutor_join_status."</td>";
        echo "<td>".$values->student_join_status."</td>";
        echo "<td>".$values->remarks."</td>";
        echo "</tr>";

        $i++;
    }
    echo "</table>";
    return ob_get_clean();

}


function sb_check_user_availablity_fn(){
?>
<!-- <form action="" method="post">
<input type="email" name="email" id="email">
<button type="submit" id="submit" >Submit</button>
</form> -->


<script>
    jQuery(document).ready(function ($) {
        $('#participate').prop('disabled', true);
        $('#form-field-stu_email').change(function (e) { 
            e.preventDefault();
                var email = $('input[type=email]').val();
                if($(this).val()){
                    jQuery.ajax({
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        method: "POST",
                        data: {
                            "email": email,
                            "action": "sb_check_user"
                        },
                        success: function(resultData) {

                            console.log(resultData);

                            var res = JSON.parse(resultData);
                            if(res.status == 1){
                                console.log( 'user eligible success');
                                // $("input[type=email]").after("<small>success!</small>");
                                $('#participate').prop('disabled', false);
                                $('#sb_ntice').remove();
                            }else{
                                $('#sb_ntice').remove();
                                $("input[type=email]").after("<small id='sb_ntice' style='color:red;margin: 5px 10px;'>You are not eligible!</small>");
                                console.log( 'user eligible fail');
                                $('#participate').prop('disabled', true);
                            }
                            
                        },
                        error: function(e) {
                            console.log(e);
                        },                            
                    });
                }
                //alert(email);
                console.log(email);
        });
    });
</script>


<?php
}
//add_shortcode( 'user_availablility', 'sb_check_user_availablity_fn' );
add_action('wp_footer','sb_check_user_availablity_fn');

add_action('wp_ajax_sb_check_user', 'sb_check_user_cb');
add_action('wp_ajax_nopriv_sb_check_user', 'sb_check_user_cb');

function sb_check_user_cb(){
    if(isset($_POST['email'])){
        $email = $_POST['email'];
        $exists = email_exists( $email );        
        if ( $exists ) {
            //echo "That E-mail is registered to user number " . $exists;
            $status = '0';
            $user = get_user_by( 'email', $email );
            if($user->roles[0] == 'student'){
                //echo 'student';
                $user_meta = get_user_meta($user->ID);
                $profile_meta = get_post_meta($user_meta['profile_id'][0]);
                $check_field = $profile_meta['phone_number'][0];
                if(!empty($check_field)){
                    $user_id = $user->ID;
                    global $wpdb;
                    $table_name = $wpdb->prefix . "sb_video_app_details";                       
                    $results = $wpdb->get_results("SELECT * FROM $table_name WHERE student_id = $user_id AND remarks = '3' ");
                    $count = count($results);
                    if($count >= 3){
                       $profile_id =  $user_meta['profile_id'][0];
                       $level = get_field('field_61d964ff0befd',$profile_id);
                        if($level == '107' || $level == '108' || $level == '112'){
                            $status = '1';   
                        }
                    }
                }                   
            }
        }else{
            $status = '0';
            //echo "That E-mail doesn't belong to any registered users on this site";
        }
        $arr["status"]= $status;
        echo json_encode($arr);
        //echo $level;
        die();
    }
}

add_action( 'elementor_pro/forms/validation', 'sb_eform_restrict_maximum_entries', 10, 2);

function sb_eform_restrict_maximum_entries( $record, $ajax_handler ) { 

	// target my form
	$target_form_id = '7ddeba2';

	// define a maximum entries 
	$max_entries = 90;

	// $record->get_form_settings('id') will be able to retrieve the form_id which is 74 from my example
	if( $target_form_id != $record->get_form_settings('id') ) return;

	// $record->get_form_settings('form_post_id') will be able to retrieve the post_id which is 74 from my example
	$total_entries = get_total_submitted_entries( $target_form_id, $record->get_form_settings('form_post_id') );

    // $submited_entries = get_submitted_entries($target_form_id, $record->get_form_settings('form_post_id'));
    // print_r($submited_entries);

	// throw error if already reached maximum entries
	if( $max_entries <= $total_entries ) {
		$ajax_handler->add_error( 'stu_email', ' We have reached our maximum participation limit of 90 students!' ); 


	}
}

function get_total_submitted_entries( $form_id, $post_id ) {

	// I will use Elementor's class to get the table names
	$elementor_submission_query = ElementorPro\Modules\Forms\Submissions\Database\Query::get_instance();

	// $elementor_submission_query->get_table_submissions() will return the submissions table name
	$q = "
		SELECT COUNT(*) FROM `{$elementor_submission_query->get_table_submissions()}` subh 
		WHERE subh.element_id = '%s' AND subh.post_id = %d
	";

	$where_values = [$form_id, $post_id];

	global $wpdb;

	$result = (int) $wpdb->get_var( $wpdb->prepare( $q , $where_values ) );
	
	return $result ;
}



// add_filter( 'elementor/widget/render_content', 'sb_hide_form_if_reach_maximum_entries', 10, 2);

// function sb_hide_form_if_reach_maximum_entries($widget_content, $widget){

// 	// target my form only
// 	$form_id = "7ddeba2";

//     if ( "form" != $widget->get_name() || $form_id != $widget->get_id() ) return $widget_content;

// 	// ElementorPro\Core\Utils::get_current_post_id() can retrieve the post_id which is 74 in my example
// 	$widget_post_id = ElementorPro\Core\Utils::get_current_post_id();

// 	// define a maximum entries 
// 	$max_entries = 90;

// 	$total_entries = get_total_submitted_entries($form_id, $widget_post_id);

// 	if( $max_entries <= $total_entries ) {
// 		return "<p> We have reached our maximum participation limit of 90 students!</p>";
// 	}

// 	return $widget_content;
// };










function get_submitted_entries( $form_id, $post_id ) {

	// I will use Elementor's class to get the table names
	$elementor_submission_query = ElementorPro\Modules\Forms\Submissions\Database\Query::get_instance();

	// $elementor_submission_query->get_table_submissions() will return the submissions table name
	$q = "
		SELECT * FROM `{$elementor_submission_query->get_table_submissions()}` subh 
		WHERE subh.element_id = '%s' AND subh.post_id = %d
	";

	$where_values = [$form_id, $post_id];

	global $wpdb;

	$result = $wpdb->get_results( $wpdb->prepare( $q , $where_values ) );
	
	return $result ;
}