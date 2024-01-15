@extends('layouts.app')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h3>Regulation Master</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <button id="add" class="btn btn-primary"><i class="fas fa-plus-circle"></i> เพิ่ม </button> 
            <button id="edit" class="btn btn-success"><i class="fas fa-edit"></i> แก้ไข</button>
            {{-- <button id="delete" class="btn btn-danger"><i class="fas fa-trash"></i> ลบ</button> --}}
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12">
            <div id="regu_grid"></div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header bg-primary ">
            <h5 class="modal-title text-white " id="exampleModalLabel">เพิ่ม Regulation</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <label for="corgroup_drop">Corgroup</label>
            <div id="corgroup_drop" ></div>
            <br>
            <label for="regu_desc">Regulation</label><br>
            <textarea type="text" rows="3" cols="55" id="regu_desc" class="form-control"> </textarea>
            
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
            <h5 class="modal-title text-white " id="exampleModalLabel">แก้ไข Regulation</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <label for="corgroup_drop_edit">Corgroup</label>
            <input id="corgroup_drop_edit" class="form-control" readonly>
            <br>
            <label for="regu_desc_edit">Regulation</label><br>
            <textarea type="text" rows="3" cols="55" id="regu_desc_edit" class="form-control"></textarea>  
        </div>
        <div class="modal-footer">
                <button id="edit_submit" type="button" class="btn btn-primary" data-dismiss="modal">ยืนยัน</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
        </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    corgrid();
});

$('#edit').off("click").on('click', function () {alert('กรุณาเลือกรายการ');});
$('#delete').off("click").on('click', function (event) { alert('กรุณาเลือกรายการ'); });

$('#add').off("click").on('click', function (event) {
        $('#add_modal').modal();
        $('#add_modal').on('shown.bs.modal', function() {
                    $("#regu_desc").focus();
                });
            $('#add_submit').off("click").on('click', function (event) {
                if (corgroup_id === undefined | !$.trim($('#regu_desc').val()) ){
                    swal({                    
                        title: "กรุณาใส่ข้อมูลให้ครบถ้วน",
                        icon: "error",
                        dangerMode: true,
                        });
                    return 'STOP';         
                }else{         
                    $.ajax({
                            type: 'GET',
                            url: '/admin/addregu/',
                            data : 
                                        {"regu_desc" : $('#regu_desc').val() ,
                                        "corgroup_id" : corgroup_id}
                                        , 
                            dataType: "JSON", 
                            success: function(response)    
                                {   
                                    if (response.result == false){

                                        alert('เกิดข้อผิดพลาด ! \nสาเหตุ : '+response.message);
                                        return "STOP";

                                    }  
                                    alert('Success !');
                                    location.reload();

                                }
                    }); 
                }
            });
    });

$('#regu_grid').on('rowselect', function (event) 
    {
        var args = event.args;
        var rowBoundIndex = args.rowindex;
        var rowData = args.row;
        $('#edit').off("click").on('click', function (event) {
            
            
            $('#edit_modal').modal();
            $('#corgroup_drop_edit').val(rowData.corgroup_description );
            $('#regu_desc_edit').val(rowData.cor_description);
            $('#edit_modal').on('shown.bs.modal', function() {
                $("#regu_desc_edit").focus();
            });
        });

        $('#edit_submit').off("click").on('click', function (event) {
            if ($('#regu_desc_edit').val() === ""){
                swal({                    
                    title: "กรุณาใส่ข้อมูลให้ครบถ้วน",
                    icon: "error",
                    dangerMode: true,
                    });
                return 'STOP';         
            }else{   
            
                $.ajax({
                            type: 'GET',
                            url: '/admin/editregu/',
                            data : 
                                    {
                                        "corgroup_id" : rowData.corgroup_id ,
                                        "cor_id" : rowData.cor_id,
                                        "regu_desc" : $('#regu_desc_edit').val() ,
                                    }, 
                            dataType: "JSON", 
                            success: function(response)    
                                {  
                                    if (response.result == false){
                                        alert('เกิดข้อผิดพลาด ! \nสาเหตุ : '+response.message);
                                        return "STOP";
                                    }
                                    alert('Success !');
                                    location.reload();
                                }
                }); 
            }
        }); 

        $('#delete').off("click").on('click', function (event) {
            if(rowData.Active == 1){
                alert('Regulation นี้ถูกใช้งานอยู่ \nไม่สามารถลบได้');
                return "STOP";
            }
            var r = confirm('ยืนยันการลบ ? \n'+rowData.cor_description);
            if (r == true) {
                $.ajax({
                    type: 'GET',
                    url: '/admin/deleteregu/',
                    data : {"cor_id" : rowData.cor_id ,
                            "corgroup_id" : rowData.corgroup_id},
                    dataType: "JSON", 
                    success: function(response)    
                        {  
                            if (response.result == false){
                                alert('เกิดข้อผิดพลาด ! \nสาเหตุ : '+response.message);
                                return "STOP";
                            }
                                alert('Success !');
                                location.reload();
                        }
                });
            }
        }); 
    });

function corgrid(){
    //Grid Data
    var source =
            {
                datatype: "json",
                datafields: [
                    
                    // { name: 'id', type: 'string'},
                    { name: 'corgroup_id', type: 'string'},
                    { name: 'corgroup_description', type: 'string'},
                    { name: 'cor_id', type: 'string'},
                    { name: 'cor_description', type: 'string'},
                    { name: 'active', type: 'int'},

                ],
                url: '/admin/getregu/',

                updaterow: function (rowid, rowdata, result) {     
             
                    updateactive(rowdata)
                    .done(function(data) {
                        
                        if (data.result == false) {
                            alert("เกิดข้อผิดพลาดในการ Active \n"+data.message);
                        } else {
                            $('#regu_grid').jqxGrid('updatebounddata', 'cells');
                        }
                    });
                    result(true);
                }
            };
            
            var dataAdapter = new $.jqx.dataAdapter(source);
            
            
        //Create Grid
        $("#regu_grid").jqxGrid(
            {
                width: 850,
                autoheight: true,
                source: dataAdapter,
                theme :'fresh',
                showfilterrow: true,
                filterable: true, 
                editable: true,
                pageable: true,
                columns: [
                { text: 'Corgroup', datafield: 'corgroup_description', width: 300 ,editable: false},
                { text: 'Regulation', datafield: 'cor_description', width: 500 ,editable: false},
                { text: 'Active', datafield: 'active',columntype: 'checkbox', width: 50 },
                ]
            });

        //Corgroup
        
        var corgroupsource =
        {
            datatype: "json",
            datafields: [
                { name: 'corgroup_id'},
                { name: 'corgroup_description'},                        
            ],
            url: '/getdata/allcorgroup'
        };
        var corgroupdata = new $.jqx.dataAdapter(corgroupsource);
        $("#corgroup_drop").jqxDropDownList({ 
            source: corgroupdata,
            width: '100%', 
            height: '40',
            autoDropDownHeight: true,
            displayMember: 'corgroup_description',
            valueMember: 'corgroup_id',   
        });
        $('#corgroup_drop').on('select', function (event) {
            var args = event.args;
            var item = $('#corgroup_drop').jqxDropDownList('getItem', args.item);
            corgroup_id = item.originalItem['corgroup_id'];
            
        });
        // $("#corgroup_drop_edit").jqxDropDownList({ 
        //     source: corgroupdata,
        //     width: '100%', 
        //     height: '40',
        //     displayMember: 'corgroup_description',
        //     valueMember: 'corgroup_id',   
        // });
        // $('#corgroup_drop_edit').on('select', function (event) {
        //     var args = event.args;
        //     var item = $('#corgroup_drop').jqxDropDownList('getItem', args.item);
        //     corgroup_id = item.originalItem['corgroup_id'];
        // });
}

function updateactive(rowdata) {
    
    return $.ajax({
        url : '/admin/activeregu/',
        type : 'GET',
        dataType : 'json',
        data : {
            "corgroup_id" : rowdata.corgroup_id,
            "corid" : rowdata.cor_id,
            "active" : rowdata.active
        }
    });
}
</script>

@endsection