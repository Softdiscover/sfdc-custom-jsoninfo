<?php
declare(strict_types=1);
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="profile" href="https://gmpg.org/xfn/11" />
     
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div class="container">

<div class="row">
<h1><?php echo esc_html(__('Show users', 'sfdc-plugin'));?></h1>
<table id="list-users" class="table table-striped">
  <thead>
    <tr>
      <th scope="col"><?php echo esc_html(__('ID', 'sfdc-plugin'));?></th>
      <th scope="col"><?php echo esc_html(__('Name', 'sfdc-plugin'));?></th>
      <th scope="col"><?php echo esc_html(__('Username', 'sfdc-plugin'));?></th>
      <th scope="col"><?php echo esc_html(__('Email', 'sfdc-plugin'));?></th>
    </tr>
  </thead>
  <tbody>
  
  </tbody>
</table>
</div>
</div>
 
<!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title"><?php echo esc_html(__('User Details', 'sfdc-plugin'));?></h4>
        <button type="button" class="close" 
        data-dismiss="modal"><i class="fa fa-times-circle"></i></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger"
        data-dismiss="modal"><?php echo esc_html(__('Close', 'sfdc-plugin'));?></button>
      </div>

    </div>
  </div>
</div>
<?php wp_footer(); ?>

</body>
</html>
