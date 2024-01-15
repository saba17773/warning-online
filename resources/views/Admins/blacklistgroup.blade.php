@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h3>Blacklist Group Master</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <button id="add" class="btn btn-primary"><i class="fas fa-plus-circle"></i> เพิ่ม </button> 
            <button id="edit" class="btn btn-success"><i class="fas fa-edit"></i> แก้ไข</button>
            <button id="delete" class="btn btn-danger"><i class="fas fa-trash"></i> ลบ</button>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12">
            <div id="bl_group_grid"></div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header bg-primary ">
            <h5 class="modal-title text-white " id="exampleModalLabel">เพิ่ม Blacklist Group</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">

                <label for="description">Description</label><br>
                <input id="description" class="form-control" >

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

                <label for="description_edit">Description</label><br>
                <input id="description_edit" class="form-control" >

        </div>
        <div class="modal-footer">
                <button id="edit_submit" type="button" class="btn btn-primary" data-dismiss="modal">ยืนยัน</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
        </div>
        </div>
    </div>
</div>
<script>

function groupgrid(){
    var source =
        {
            datatype: "json",
            datafields: [
                { name: 'blacklist_group', type: 'string'},
                { name: 'blacklist_description', type: 'string'},
                { name: 'active', type: 'int'},
            ],
            url: '/admin/getblacklistgroup/',

            updaterow: function (rowid, rowdata, result) {     
             
             updateactive(rowdata)
             .done(function(data) {
                 
                 if (data.result == false) {
                     alert("เกิดข้อผิดพลาดในการ Active \n"+data.message);
                 } else {
                     $('#bl_group_grid').jqxGrid('updatebounddata', 'cells');
                 }
             });
             result(true);
         }
            
        };
        
        var dataAdapter = new $.jqx.dataAdapter(source);
        
        
    //Create Grid
    $("#bl_group_grid").jqxGrid(
        {
            width: 550,
            autoheight: true,
            source: dataAdapter,
            theme :'fresh',
            showfilterrow: true,
            filterable: true, 
            editable: true,
            pageable: true,
            columns: [
            { text: 'Description', datafield: 'blacklist_description', width: 500 },
            { text: 'Active', datafield: 'active', width: 50 ,columntype: 'checkbox',},
            
            ]
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
    if($('#description').val() == ""){
        alert('กรุณากรอกข้อมูลให้ครบถ้วน');
   
    
    }else{
        var description = $('#description').val();
        submitajax('addblacklistgroup',null,description);
    }
    
});
$("#edit").click(function () {
    var rowData = getrowdata('#bl_group_grid');
      if (typeof rowData == "undefined")
      {
        alert('กรุณาเลือกรายการ');
      }
      else
      {
        $('#edit_modal').modal();
        $('#description_edit').val(rowData.blacklist_description);
      }      
});

$('#edit_submit').click(function () {
    var rowData = getrowdata('#bl_group_grid');
    var id = rowData.blacklist_group;
    var description = $('#description_edit').val();
    submitajax('editblacklistgroup',id,description); 
});

function submitajax(mode,id,description){
    $.ajax({
        type: 'POST',
        url: '/admin/'+mode,
        data : 
            {
                '_token' : '{{ csrf_token() }}',
                "id" : id,
                "description" : description ,
            }, 
        dataType: "JSON", 
        success: function(response)    
            {
                console.log(response);
                if (response.result == true){
                    alert(response.message);
                    location.reload();
                }else{
                    alert('เกิดข้อผิดพลาด ! \nสาเหตุ : '+response.message);
                }  
            }
    });
}
$("#delete").click(function () {
    var rowData = getrowdata('#bl_group_grid');
    if(rowData.Active == 1){
        alert('Blacklist Group นี้ถูกใช้งานอยู่ \nไม่สามารถลบได้');
        return "STOP";
    }
    if (typeof rowData == "undefined"){
        alert('กรุณาเลือกรายการ');
    }else{
        var r = confirm('ยืนยันการลบ ?');
        if (r == true) {
            submitajax('deleteblacklistgroup',rowData.blacklist_group);
        }
    }     
});

function updateactive(rowdata) {
    
    return $.ajax({
        url : '/admin/activeblacklist_group/',
        type : 'GET',
        dataType : 'json',
        data : {
            "id" : rowdata.blacklist_group,
            "active" : rowdata.active
        }
    });
}

$(document).ready(function() {
    groupgrid();
});


</script>
@endsection