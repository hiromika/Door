<div class="container">
<div class="row">
	
	<?php 

		$user = $proses->user_byid($ses['id']);
	 ?>
	    <div class="col-md-3 text-center" style="padding-bottom: 15px;">
          <p style="color: grey;">Foto Profile</p>
          <form action="proses_link.php?action=change_image" method="POST" id="fot" accept-charset="utf-8" enctype="multipart/form-data">
          <img src="<?php echo $user[0]['user_image'] ?>"" class="img-responsive" alt="profile foto" width="100%" height="180">
            <a href="#" class="profil-foto btn btn-primary btn-block">Edit</a>
            <input type="text" name="id" value="<?php echo $ses['id'] ?>" hidden placeholder="">
            <input type="file" name="user_image" id="foto" class="input-foto" onchange="javascript:this.form.submit()" style="display: none;">
          </form>
        </div>
        <div class="col-md-8">
			<form action="proses_link.php?action=save_profile" method="POST" accept-charset="utf-8">
			  <input type="hidden" name="user_id" id="user_id" value="<?php echo $ses['id'] ?>"/>
	          <div class="form-group">
	            <label>Username :</label>
	            <input type="text" name="user_name" id="user_name" value="<?php echo $user[0]['user_name'] ?>" class="form-control" required="">
	          </div>
	          <div class="form-group">
	            <label>E-mail :</label>
	            <input type="email" name="user_email" id="user_email" value="<?php echo $user[0]['user_email'] ?>" class="form-control" required="">
	          </div>
	          <div class="form-group">
	            <label>Address :</label>
	            <textarea name="user_address" id="user_address" class="form-control" required=""><?php echo $user[0]['user_address'] ?></textarea>
	          </div>
	          <div class="form-group">
	            <label>Phone Number :</label>
	            <input type="number" name="user_phone" id="user_phone" value="<?php echo $user[0]['user_phone'] ?>" class="form-control" required="">
	          </div> 
	          <div class="form-group">
	            <label>User Password</label>
	            <input type="password" name="user_password" id="user_password" class="form-control">
	          </div>

	          <div class="row">
	          	<div class="col-md-12 text-center">
	          	<button type="submit" class="btn btn-primary btn-block">Save</button>
	          	</div>
	          </div>
			</form>
        </div>
</div>
</div>

<script type="text/javascript">
	$(function(){
	    $(".profil-foto").on('click', function(e){
	        e.preventDefault();
	        $(this).parent().find(".input-foto").trigger('click');
	    });
	});
</script>