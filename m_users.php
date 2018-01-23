<div class="container">
  
<div class="col-md-12">
<!-- Button to Open the Modal -->
<button type="button" class="btn btn-sm  btn-primary" data-toggle="modal" id="user_add">
 Add User
</button>
<hr>
  <div style="overflow-x: auto;">
    
  <table class="table table-bordered " id="tb_users">
    <thead>
      <tr class="bg-info text-light">
        <th>No</th>
        <th>Name</th>
        <th>E-mail</th>
        <th>Address</th>
        <th>Phone number</th>
        <th>Role</th>
        <th>Photo</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      
    </tbody>
  </table>
  </div>
</div>


<!-- The Modal -->
<div class="modal fade" id="modal_form">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add User</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form  method="POST" class="form" accept-charset="utf-8" id="user_form" enctype="multipart/form-data">
          <input type="hidden" name="user_id" id="user_id" />
          <input type="hidden" name="operation" id="operation" />
          <div class="form-group">
            <label>Name :</label>
            <input type="text" name="user_name" id="user_name" class="form-control" required="">
          </div>
          <div class="form-group">
            <label>E-mail :</label>
            <input type="email" name="user_email" id="user_email" class="form-control" required="">
          </div>
          <div class="form-group">
            <label>Address :</label>
            <textarea name="user_address" id="user_address" class="form-control" required=""></textarea>
          </div>
          <div class="form-group">
            <label>Phone Number :</label>
            <input type="number" name="user_phone" id="user_phone" class="form-control" required="">
          </div>  
          <div class="form-group">
            <label>Role :</label>
            <select name="role" class="form-control" id="role">
              <option disabled="" selected="">~ Select Role ~</option>
              <?php $role = $proses->Get_role();
              print_r($role);
              foreach ($role as $key => $value) { ?>
              <option value="<?php echo $value['id']; ?>"><?php echo $value['name_rule']; ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group">
            <label>User Password</label>
            <input type="password" name="user_password" id="user_password" class="form-control">
          </div>
          <div class="form-group">
            <label>Photo</label>
            <input type="file" name="user_image" id="user_image" class="form-control">
            <span id="user_uploaded_image"></span>
          </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="in_user">Submit</button>
        </form>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
</div>
<script type="text/javascript">

var tb_user

$(document).ready(function(){

  var url = 'proses_link.php?action=get_user'; 
  tb_user = $('#tb_users').DataTable({
    "responsive": true,
        "processing": true,
        "autoWidth": false,
        "ajax"  :{
        'url': url,
        'type': 'POST',
        'dataType':'json',
      },
          "columns":[
          {'data' :  null, 'searchable': false, 'orderable': false, 'targets': 0, 'witdh' : '5%',},
          {'data' : 'user_name'},
          {'data' : 'user_email'},
          {'data' : 'user_address'},
          {'data' : 'user_phone'},
          {'data' : 'name_rule'},
          ],
          "columnDefs" :[
            {
          "targets" : [6],
          "data"    : 'user_image',
          "render" : function(data,type,full,meta){
            var img = "<img src='"+data+"' class='img-thumbnail' width='50' height='35' />";
            return "<center>"+img+"</center>";
              }
            },{
          "targets" : [7],
          "data"    : 'user_id',
          "render" : function(data,type,full,meta){
            var edit = "<button type='button' name='update' class='btn btn-sm btn-success update' id='"+data+"'><span class='fa fa-edit'>&nbspEdit</span></button>&nbsp";
            var del = "<button type='button' name='delete' class='btn btn-danger btn-sm delete' id='"+data+"'><span class='fa fa-trash'>&nbspDelete</span></button>";
            return "<center>"+edit+del+"</center>";
              }
            }
          ],
  });
   tb_user.on( 'order.dt search.dt', function () {
              tb_user.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                  cell.innerHTML = i+1;
              } );
       }).draw();
});

$("#user_add").click(function(e){
	$('#user_form')[0].reset();
	$('.modal-title').text("Add User");
	$('#action').val("Add");
	$('#operation').val("Add");
	$('#user_uploaded_image').html('');       
    $("#modal_form").modal({
        show     : true,
        backdrop : 'static',
        keyboard : false,
    });
});


$(document).on('submit','#user_form',function(e){
	e.preventDefault();
	$.ajax({
		url: 'proses_link.php?action=in_user',
		type: 'POST',
		data:new FormData(this),
		contentType:false,
		processData:false,
				success:function(data)
				{
					alert(data);
					$('#user_form')[0].reset();
					$('#modal_form').modal('hide');
					tb_user.ajax.reload();
				}
	});

});

$(document).on('click', '.update', function(){
	var user_id = $(this).attr("id");
	$.ajax({
		url:"proses_link.php?action=get_user_byid",
		method:"POST",
		data:{user_id:user_id},
		dataType:"json",
		success:function(data)
		{
			$('#modal_form').modal('show');
			$('.modal-title').text("Edit User");
			$('#user_id').val(user_id);
			$('#user_name').val(data.user_name);
			$('#user_email').val(data.user_email);
			$('#user_address').val(data.user_address);
			$('#user_phone').val(data.user_phone);
			$('#role').val(data.rule);
			$('#user_uploaded_image').html(data.user_image);
			$('#action').val("Edit");
			$('#operation').val("Edit");
		}
	})
});


	$(document).on('click', '.delete', function(){
		var user_id = $(this).attr("id");
		if(confirm("Are you sure you want to delete this?"))
		{
			$.ajax({
				url:"proses_link.php?action=user_delete",
				method:"POST",
				data:{user_id:user_id},
				success:function(data)
				{
					alert(data);
					tb_user.ajax.reload();
				}
			});
		}
		else
		{
			return false;	
		}
	});

</script>
