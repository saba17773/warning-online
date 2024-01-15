@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h3>Users Master</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            {{-- <button id="add" class="btn btn-primary"><i class="fas fa-plus-circle"></i> เพิ่ม </button>  --}}
            <button id="edit" class="btn btn-success"><i class="fas fa-edit"></i> แก้ไข</button>
            {{-- <button id="delete" class="btn btn-danger"><i class="fas fa-trash"></i> ลบ</button> --}}
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12">
            <div id="user_grid"></div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header bg-primary ">
            <h5 class="modal-title text-white " id="exampleModalLabel">เพิ่ม User</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
                <label for="emplid">รหัสพนักงาน</label>
                <input id="emplid" class="form-control" type="number" >
                <br>
                <label for="username">Username</label><br>
                <input id="username" class="form-control" >
                <br>
                <label for="name">ชื่อ - สกุล</label><br>
                <input id="name" class="form-control" >
                <br>
                <label for="email">E-mail</label><br>
                <input id="email" class="form-control" >
                <br>
                <label for="level">Level </label><br>
                <input type="radio" name="level" value="1" readonly> User 
                <input type="radio" name="level" value="2" readonly> Admin 
        </div>
        <div class="modal-footer">
                <button id="add_submit" type="button" class="btn btn-primary" data-dismiss="modal">ยืนยัน</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
        </div>
        </div>
    </div>
</div>
<!-- Edit Modal -->
<div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header bg-success ">
                <h5 class="modal-title text-white " id="exampleModalLabel">แก้ไข User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label for="emplid_edit">รหัสพนักงาน</label>
                <input id="emplid_edit" class="form-control" disabled>
                <br>
                <label for="username_edit">Username</label><br>
                <input id="username_edit" class="form-control" disabled>
                <br>
                <label for="name_edit">ชื่อ - สกุล</label><br>
                <input id="name_edit" class="form-control" disabled>
                <br>
                <label for="email_edit">E-mail</label><br>
                <input id="email_edit" class="form-control" disabled>
                <br>
                <label for="level_edit">Level </label><br>
                <input type="radio" name="level_edit" value="1" > User 
                <input type="radio" name="level_edit" value="2" > Admin 
            </div>
            <div class="modal-footer">
                    <button id="edit_submit" type="button" class="btn btn-primary" data-dismiss="modal">ยืนยัน</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
            </div>
            </div>
        </div>
    </div>
<script>

function grid(){
    var source =
        {
            datatype: "json",
            datafields: [
                { name: 'id', type: 'string'},
                { name: 'emplid', type: 'string'},
                { name: 'userid', type: 'string'},
                { name: 'name', type: 'string'},
                { name: 'email', type: 'string'},
                { name: 'company', type: 'string'},
                { name: 'level', type: 'string'},
                { name: 'active', type: 'int'},
            ],
            url: '/admin/getuser/',

            updaterow: function (rowid, rowdata, result) {     

                updateactive(rowdata)
                .done(function(data) {
                    
                    if (data.result == false) {
                        alert("เกิดข้อผิดพลาดในการ Active \n"+data.message);
                    } else {
                        $('#user_grid').jqxGrid('updatebounddata', 'cells');
                    }
                });
                result(true);
            }
        };
        
        var dataAdapter = new $.jqx.dataAdapter(source);
        
        
    //Create Grid
    $("#user_grid").jqxGrid(
        {
            width: 900,
            autoheight: true,
            source: dataAdapter,
            theme :'fresh',
            showfilterrow: true,
            filterable: true, 
            pageable: true,
            editable: true,
            columns: [
            { text: 'Emplid', datafield: 'emplid', width: 100,editable: false },
            { text: 'Username', datafield: 'userid', width: 150,editable: false },
            { text: 'Name', datafield: 'name', width: 200,editable: false },
            { text: 'E-mail', datafield: 'email', width: 300,editable: false },
            { text: 'Company', datafield: 'company', width: 50,editable: false },
            { text: 'Level', datafield: 'level', width: 50,editable: false },
            { text: 'Active', datafield: 'active',columntype: 'checkbox', width: 50 },
            ]
        });
}

function updateactive(rowdata) {
    
    return $.ajax({
        url : '/admin/activeusers/',
        type : 'GET',
        dataType : 'json',
        data : {
            "id" : rowdata.id,
            "active" : rowdata.active
        }
    });
}


function getrowdata(grid_name){
    var selectedrowindex = $(grid_name).jqxGrid('getselectedrowindex');
    var rowData = $(grid_name).jqxGrid('getrowdata', selectedrowindex);
    return  rowData
}

$("#add").click(function () {
    $('#add_modal').modal();
});

$('#add_submit').click(function () {
    if($('#emplid').val() == ""
        |$('#username').val() == ""
        |$('#name').val() == ""
        |$('#email').val() == ""
        |$('input:radio[name=level]:checked').val() === undefined){
        alert('กรุณากรอกข้อมูลให้ครบถ้วน');
    }else{
        submitajax('adduser',null,$('#emplid').val(),$('#username').val(),$('#name').val(),$('#email').val(),$('input:radio[name=level]:checked').val());
    }
});

$('#edit_submit').click(function () {
    var rowData = getrowdata('#user_grid');
    var level = $('input:radio[name=level_edit]:checked').val();
    submitajax('edituser',rowData.id,rowData.emplid,rowData.userid,rowData.name,rowData.email,level);
    
});

function submitajax(mode,id,emplid,userid,name,email,level){
    $.ajax({
        type: 'POST',
        url: '/admin/'+mode,
        data : 
            {
                '_token' : '{{ csrf_token() }}',
                "id" : id,
                "emplid" : emplid ,
                "userid" : userid ,
                "name" : name,
                "email" : email,
                "level" : level,
            }, 
        dataType: "JSON", 
        success: function(response)    
            {
                if (response.result == true){
                    alert('Success !');
                    location.reload();
                }else{
                    alert('เกิดข้อผิดพลาด ! \nสาเหตุ : '+response.message);
                }  
            }
    });
}
$("#delete").click(function () {
    var rowData = getrowdata('#user_grid');
    if (typeof rowData == "undefined"){
        alert('กรุณาเลือกรายการ');
    }else{
        var r = confirm('ยืนยันการลบ ? \n'+rowData.userid);
        if (r == true) {
            submitajax('deleteuser',rowData.id);
        }
    }     
});
$("#edit").click(function () {
    var rowData = getrowdata('#user_grid');
      if (typeof rowData == "undefined")
      {
        alert('กรุณาเลือกรายการ');
      }
      else
      {
        console.log(rowData);
        $('#edit_modal').modal();
        $('#emplid_edit').val(rowData.emplid);
        $('#username_edit').val(rowData.userid);
        $('#name_edit').val(rowData.name);
        $('#email_edit').val(rowData.email);
        $("input:radio[name=level_edit][value="+rowData.level+"]").prop( 'checked', true );
      }      
});

$(document).ready(function() {
    grid();
});


</script>
@endsection