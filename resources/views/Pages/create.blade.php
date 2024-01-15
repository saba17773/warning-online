@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-secondary ">
                <div class="card-header bg-primary  text-white text-center">
                    <font size=5>สร้างใบเตือน</font>
                </div>
                <div class="card-body">
                    @csrf

                    <div class="form-group row">
                        <label for="emplid" class="col-md-4 col-form-label text-md-right">รหัสพนักงาน</label>
                        <div class="col-md-6">
                            <input type="number" class="form-control" id="emplid" name="emplid" required readonly autofocus>
                        </div>
                        <div class="col-sm">
                            <button id="search" type="button" class="btn btn-outline-danger"><i class="fas fa-search"></i> ค้นหา </button>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">ชื่อ - สกุล</label>
                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control" name="name" required readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="postition" class="col-md-4 col-form-label text-md-right">ตำแหน่ง</label>
                        <div class="col-md-6">
                            <input id="position" class="form-control" name="position" required readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="department" class="col-md-4 col-form-label text-md-right">แผนก</label>
                        <div class="col-md-6">
                            <input id="department" type="department" class="form-control" name="department" required readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="division" class="col-md-4 col-form-label text-md-right">ฝ่าย</label>
                        <div class="col-md-6">
                            <input id="division" type="division" class="form-control" name="division" required readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="division" class="col-md-4 col-form-label text-md-right">Division Code</label>
                        <div class="col-md-6">
                            <input id="divisioncode" class="form-control" name="divisioncode" readonly>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="company" class="col-md-4 col-form-label text-md-right">บริษัท</label>
                        <div class="col-md-6">
                            <input id="company" type="company" class="form-control" name="company" required readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="warning_date" class="col-md-4 col-form-label text-md-right">วันที่กระทำความผิด</label>

                        <div class="col-md-6">
                            <div id="warning_date" name="warning_date"></div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="follow_date" class="col-md-4 col-form-label text-md-right">วันที่ตักเตือน</label>
                        <div class="col-md-6">
                            <div id="follow_date" name="follow_date"></div>
                        </div>
                    </div>

                    <div class="form-group row" id="corgroup_grid">
                        <label for="corgroup_id" class="col-md-4 col-form-label text-md-right">สาเหตุกระทำความผิด</label>
                        <div class="col-md-6">
                            <div id="corgroup_id" name="corgroup_id"></div>
                        </div>
                    </div>

                    <div class="form-group row" id="cor_grid">
                        <label for="cor" class="col-md-4 col-form-label text-md-right">ระเบียบความผิด</label>
                        <div class="col-md-6">
                            <div id="cor" name="cor"></div>
                            {{-- <input id="cordata" name="cordata" hidden>                        --}}
                        </div>
                    </div>

                    <div class="form-group row" id="penalty_qty_grid">
                        <label for="penalty_qty" class="col-md-4 col-form-label text-md-right">ครั้งที่</label>
                        <div class="col-md-6">
                            <input id="penalty_qty" class="form-control" name="penalty_qty" required readonly>
                            {{-- <div id="warning_qty" name="warning_qty">1</div>                           --}}
                        </div>
                    </div>

                    <div class="form-group row" id="penalty_grid">
                        <label for="penalty" class="col-md-4 col-form-label text-md-right">บทลงโทษ</label>
                        <div class="col-md-6">
                            <div id="penalty" name="penalty"></div>
                            {{-- <input id="penaltydata" name="penaltydata" hidden>                             --}}
                        </div>
                    </div>

                    <div class="form-group row" id="penalty_start_grid">
                        <label for="penalty_start" class="col-md-4 col-form-label text-md-right">วันพักงานเริ่มต้น</label>

                        <div class="col-md-6">
                            <div id="penalty_start" class="form-control" name="penalty_start"></div>
                        </div>
                    </div>

                    <div class="form-group row" id="penalty_end_grid">
                        <label for="penalty_end" class="col-md-4 col-form-label text-md-right">วันพักงานสิ้นสุด</label>

                        <div class="col-md-6">
                            <div id="penalty_end" class="form-control" name="penalty_end"></div>
                        </div>
                    </div>

                    <div class="form-group row" id="remark_grid">
                        <label for="remark" class="col-md-4 col-form-label text-md-right">Remark</label>
                        <div class="col-md-6">
                            <!-- <input id="remark" class="form-control" name="remark" style="width:358px; height:80px;"> -->
                            <textarea rows="2" cols="20" id="remark" class="form-control" name="remark" wrap="hard" style="width:358px; height:80px;"></textarea>
                        </div>
                    </div>

                    <input id=" loginuser" name="loginuser" value="{{ Auth::user()->userid }}" hidden>
                    <input id="logincompany" name="logincompany" value="{{ Auth::user()->company }}" hidden>

                    <br>
                    <hr noshade>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button id="go" name="go" class="btn btn-primary "><i class="far fa-edit"></i> บันทึก</button>
                            <button id="back" name="back" class="btn btn-danger float-right "><i class="fas fa-home"></i> กลับหน้าหลัก</button>
                            {{-- <a href="/home" class="btn btn-danger  float-right"><i class="fas fa-home"></i> กลับหน้าหลัก</a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search Modal -->
<div class="modal fade " id="search_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary ">
                <h5 class="modal-title text-white " id="exampleModalLabel">ค้นหา</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="empl_grid"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        //Hide Element
        $('#penalty_start_grid').hide();
        $('#penalty_end_grid').hide();
        $('#penalty_grid').hide();
        $('#penalty_qty_grid').hide();
        $('#cor_grid').hide();
        $('#corgroup_grid').hide();
        $('#remark_grid').hide();

        // Create a jqxDateTimeInput
        // $("#warning_date").jqxDateTimeInput({culture: 'th-TH'});
        // $("#follow_date").jqxDateTimeInput({culture: 'th-TH'});
        // $("#penalty_start").jqxDateTimeInput({culture: 'th-TH'});
        // $("#penalty_end").jqxDateTimeInput({culture: 'th-TH'});

        $("#warning_date").jqxDateTimeInput({
            showTimeButton: true,
            width: '100%',
            height: 40,
            formatString: 'dd/MM/yyyy HH:mm'
        });
        $("#follow_date").jqxDateTimeInput({
            showTimeButton: true,
            width: '100%',
            height: 40,
            formatString: 'dd/MM/yyyy HH:mm'
        });



        // $('#warning_date').on('valueChanged', function (event) 
        // {  
        //     console.log(event.args.date); 

        // });

        var corgroup_id = 1;
        var emplid;
        var penalty_s_value = null;
        var penalty_e_value = null;

        $('#search').on('click', function(event) {

            $('#search_modal').modal();

            //Prepareing Data
            var source = {
                datatype: "json",
                datafields: [
                    // { name: 'id', type: 'string'},
                    {
                        name: 'CODEMPID',
                        type: 'string'
                    },
                    {
                        name: 'emplname',
                        type: 'string'
                    },
                    {
                        name: 'EMAIL',
                        type: 'string'
                    },
                    {
                        name: 'DIVISIONNAME',
                        type: 'string'
                    },
                    {
                        name: 'DIVISIONCODE',
                        type: 'int'
                    },
                    {
                        name: 'POSITIONNAME',
                        type: 'string'
                    },
                    {
                        name: 'DEPARTMENTNAME',
                        type: 'string'
                    },
                    {
                        name: 'COMPANYNAME',
                        type: 'string'
                    },
                ],
                url: '/getdata/empl/'
            };

            var dataAdapter = new $.jqx.dataAdapter(source);
            //Create Grid
            $("#empl_grid").jqxGrid({
                width: 760,
                autoheight: true,
                source: dataAdapter,
                theme: 'fresh',
                showfilterrow: true,
                filterable: true,
                pageable: true,
                columnsresize: true,
                columns: [
                    // { text: 'ID', datafield: 'corgroup_id', width: 50 },
                    {
                        text: 'รหัสพนักงาน',
                        datafield: 'CODEMPID',
                        width: 80
                    },
                    {
                        text: 'ชื่อ - สกุล',
                        datafield: 'emplname',
                        width: 180
                    },
                    {
                        text: 'ตำแหน่ง',
                        datafield: 'DEPARTMENTNAME',
                        width: 120
                    },
                    {
                        text: 'ฝ่าย',
                        datafield: 'DIVISIONNAME',
                        width: 120
                    },
                    {
                        text: 'Division Code',
                        datafield: 'DIVISIONCODE',
                        width: 50
                    },
                    {
                        text: 'บริษัท',
                        datafield: 'COMPANYNAME',
                        width: 60
                    },
                ]
            });
            $('#empl_grid').off("click").on('rowselect', function(event) {
                var args = event.args;
                var rowData = args.row;
                // console.log(rowData);
                // var userid   = (rowData.EMAIL).substring(0, (rowData.EMAIL).lastIndexOf("@"));
                // $('#userid').val(userid);
                $('#emplid').val(rowData.CODEMPID);
                $('#name').val(rowData.emplname);
                $('#email').val(rowData.EMAIL);
                $('#position').val(rowData.POSITIONNAME);
                $('#department').val(rowData.DEPARTMENTNAME);
                $('#company').val(rowData.COMPANYNAME);
                $('#division').val(rowData.DIVISIONNAME);
                $('#divisioncode').val(rowData.DIVISIONCODE);
                $("#search_modal .close").click()
                $('#corgroup_grid').show();
                emplid = rowData.CODEMPID;
                getpenaltyqty();



            });
        });

        $("#back").on('click', function(e) {
            console.log($('#penalty_qty').val());
        });


        $("#go").on('click', function(e) {
            $('#go').attr("disabled", true);
            $('#go').html('<i class="fas fa-spinner"></i> กำลังประมวลผล');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            // Check if emplid is selected ?
            if ($("#emplid").val() == "") {
                alert('กรุณาใส่รหัสพนักงาน');
                $('#go').attr("disabled", false);
                $('#go').html('<i class="far fa-edit"></i> บันทึก');
                return "nope"; //STOP 
            }

            // Check if emplid is selected ?
            if ($("#corgroup_id").val() == "") {
                alert('กรุณาเลือกสาเหตุความผิด');
                $('#go').attr("disabled", false);
                $('#go').html('<i class="far fa-edit"></i> บันทึก');
                return "nope"; //STOP 
            }


            // Get all selected records.
            var rows = $("#cor").jqxGrid('selectedrowindexes');
            var selectedRecords = new Array();
            for (var m = 0; m < rows.length; m++) {
                var row = $("#cor").jqxGrid('getrowdata', rows[m]);
                selectedRecords[selectedRecords.length] = row["cor_id"];
            }

            var cor_id = selectedRecords;

            // Check if ระเบียบความผิด is selected ?
            if (cor_id == "") {
                alert('กรุณาเลือกระเบียบความผิด');
                $('#go').attr("disabled", false);
                $('#go').html('<i class="far fa-edit"></i> บันทึก');
                return "nope"; //STOP 
            }

            var rows = $("#penalty").jqxGrid('selectedrowindexes');
            var selectedRecords2 = new Array();
            for (var m = 0; m < rows.length; m++) {
                var row = $("#penalty").jqxGrid('getrowdata', rows[m]);
                selectedRecords2[selectedRecords2.length] = row["Penalty_ID"];
            }
            var penalty_id = selectedRecords2;


            // Check if บทลงโทษ is selected ?
            if (penalty_id == "") {
                alert('กรุณาเลือกบทลงโทษ');
                $('#go').attr("disabled", false);
                $('#go').html('<i class="far fa-edit"></i> บันทึก');
                return "nope"; //STOP 
            }

            var warning_value = $('#warning_date').jqxDateTimeInput('value');
            var follow_value = $('#follow_date').jqxDateTimeInput('value');
            var momentwarning = moment(warning_value).format('MM-D-YYYY HH:mm:ss');
            var momentfollow = moment(follow_value).format('MM-D-YYYY HH:mm:ss');


            if (warning_value > follow_value) {
                alert('วันที่กระทำผิดต้องน้อยกว่าวันที่ตักเตือน');
                $('#go').attr("disabled", false);
                $('#go').html('<i class="far fa-edit"></i> บันทึก');
                return 'break';
            }

            penalty_s_value = $('#penalty_start').jqxDateTimeInput('value');
            penalty_e_value = $('#penalty_end').jqxDateTimeInput('value');
            var momentpenaltystart = moment(penalty_s_value).format('MM-D-YYYY');
            var momentpenaltyend = moment(penalty_e_value).format('MM-D-YYYY');

            if (penalty_s_value) {
                if (penalty_s_value > penalty_e_value) {
                    alert('วันพักงานเริ่มต้นต้องน้อยกว่าวันพักงานสิ้นสุด');
                    return 'break';
                }
                if (follow_value > penalty_s_value) {
                    alert('วันที่ตักเตือนต้องน้อยกว่าวันพักงานเริ่มต้น')
                    return 'break';
                }
            }

            //INSERT fuction
            $.ajax({
                type: 'POST',
                url: '/warns/insert',
                data: {
                    emplid: emplid,
                    warning_date: momentwarning,
                    follow_date: momentfollow,
                    position: $('#position').val(),
                    department: $('#department').val(),
                    division: $('#division').val(),
                    divisioncode: $('#divisioncode').val(),
                    company: $('#company').val(),
                    corgroup_id: corgroup_id,
                    cor_id: cor_id,
                    penalty_id: penalty_id,
                    penalty_qty: $('#penalty_qty').val(),
                    penalty_start: momentpenaltystart,
                    penalty_end: momentpenaltyend,
                    logincompany: "{{ Auth::user()->company }}",
                    loginuser: "{{ Auth::user()->userid }}",
                    remark: $('#remark').val(),
                },

                success: function(response) {

                    alert(response.email);
                    // อันใหม่ เดี๋ยวได้ยกเลิกเร็วๆนี้
                    if (response.result == true) {
                        var warning_no = response.warning_no
                        $.ajax({
                            type: "POST",
                            url: "/email/sendmail",
                            data: {
                                warning_no: response.warning_no,
                                email: response.email,
                                level: 1,
                                type: 'user'
                            },
                            dataType: "JSON",
                            success: function(response) {
                                if (response.result == true) {
                                    alert('Create Successful');
                                    window.location = "/warns/email/" + warning_no;

                                } else {
                                    alert('เกิดข้อผิดพลาด ! \n:: ' + response.message);
                                }

                            }
                        });
                    } else {
                        // console.log(response);
                        alert('เกิดข้อผิดพลาด ! \nสาเหตุ : ' + response.message);
                        $('#go').attr("disabled", false);
                        $('#go').html('<i class="far fa-edit"></i> บันทึก');
                    }
                    // End อันใหม่


                    // เก็บไว้ก่อน เดี๋ยวได้กลับมาใช้อันเดิม    
                    //     if (response.result == true){
                    //         alert('Create Successful !');
                    //         // window.location = "/warns/email/"+response.warning_no;
                    // }else{
                    //     alert('เกิดข้อผิดพลาด ! \nสาเหตุ : '+response.data );
                    //     $('#go').attr("disabled", false);
                    //     $('#go').html('<i class="far fa-edit"></i> บันทึก');
                    // }


                },
            });
        });


        //สาเหตุความผิด 
        var corgroupsource = {
            datatype: "json",
            datafields: [{
                    name: 'corgroup_id'
                },
                {
                    name: 'corgroup_description'
                },
            ],
            url: '/getdata/corgroup'
        };

        var corgroupdata = new $.jqx.dataAdapter(corgroupsource);
        // Create a jqxDropDownList
        $("#corgroup_id").jqxDropDownList({

            source: corgroupdata,
            // filterable: true, 
            width: '100%',
            height: '40',
            displayMember: 'corgroup_description',
            valueMember: 'corgroup_id',

        });
        $("#corgroup_id").jqxDropDownList('selectedIndex', '0');
        $('#corgroup_id').on('select', function(event) {
            var args = event.args;
            var item = $('#corgroup_id').jqxDropDownList('getItem', args.item);
            corgroup_id = item.originalItem['corgroup_id'];


            // var userid   = (item.originalItem['email']).substring(0, (item.originalItem['email']).lastIndexOf("@"));
            // var emplid = (item.originalItem['emplid']);

            getpenaltyqty(); //Re-get Penalty QTY
            //Unhide Grid

            $('#penalty_start_grid').show();
            $('#penalty_end_grid').show();
            $('#penalty_grid').show();
            $('#penalty_qty_grid').show();
            $('#penalty_start').jqxDateTimeInput({
                disabled: true,
                value: null,
                width: '100%',
                height: 40
            });
            $('#penalty_end').jqxDateTimeInput({
                disabled: true,
                value: null,
                width: '100%',
                height: 40
            });
            // $("#penalty_start").jqxDateTimeInput({ disabled: true,width: '100%',height: 40,value : new Date() , formatString: 'dd-MMM-yyyy'  });
            // $("#penalty_end").jqxDateTimeInput({ disabled: true,width: '100%',height: 40,value : new Date() , formatString: 'dd-MMM-yyyy'  });

            $('#cor_grid').show();
            $('#remark_grid').show();
            $("#cor").jqxGrid('clearselection');
            // $("#penalty").jqxGrid('clearselection');

            //Reget PenaltyQTY when Reselect corgroup_id
            $.ajax({
                type: "GET",
                url: "/getdata/penaltyqty/" + emplid + "/" + corgroup_id,
                data: [{
                        "emplid": emplid
                    },
                    {
                        "corgroup_id": corgroup_id
                    },
                ],
                dataType: "JSON",
                success: function(response) {
                    var integer = parseInt(response[0].penalty_qty, 10);
                    $("#penalty_qty").val(integer + 1);
                }

            });

            //ระเบียบความผิด

            var corsource = {
                datatype: "json",
                data: corgroup_id,
                datafields: [{
                        name: 'cor_id'
                    },
                    {
                        name: 'cor_description'
                    },
                ],
                url: '/getdata/cor/' + corgroup_id
            };

            var cordata = new $.jqx.dataAdapter(corsource);
            // Create a jqxDropDownList
            $("#cor").jqxGrid({
                width: '100%',
                source: cordata,
                selectionmode: 'checkbox',
                showheader: false,
                autorowheight: true,
                autoheight: true,
                altrows: true,
                columns: [{
                    text: 'เลือกทั้งหมด',
                    datafield: 'cor_description',
                    width: '91%'
                }, ]
            });

            //END ระเบียบความผิด

            //บทลงโทษ

            var penaltysource = {
                datatype: "json",
                datafields: [{
                        name: 'Penalty_ID'
                    },
                    {
                        name: 'Penalty_Description'
                    },
                ],
                url: '/getdata/penalty'
            };

            var penaltydata = new $.jqx.dataAdapter(penaltysource);
            // Create a jqxDropDownList
            $("#penalty").jqxGrid({
                width: '100%',
                source: penaltydata,
                selectionmode: 'checkbox',
                showheader: false,
                autorowheight: true,
                autoheight: true,
                altrows: true,
                columns: [{
                    text: 'เลือกทั้งหมด',
                    datafield: 'Penalty_Description',
                    width: '91%'
                }, ]
            });
            $('#penalty').on('rowselect', function(event) {
                if (event.args.rowindex == 2) {
                    $("#penalty_start").jqxDateTimeInput({
                        disabled: false,
                        width: '100%',
                        height: 40,
                        value: new Date(),
                        formatString: 'dd/MM/yyyy'
                    });
                    $("#penalty_end").jqxDateTimeInput({
                        disabled: false,
                        width: '100%',
                        height: 40,
                        value: new Date(),
                        formatString: 'dd/MM/yyyy'
                    });
                }
            });
            $('#penalty').on('rowunselect', function(event) {
                if (event.args.rowindex == 2) {
                    $('#penalty_start').jqxDateTimeInput({
                        disabled: true,
                        value: null,
                    });
                    $('#penalty_end').jqxDateTimeInput({
                        disabled: true,
                        value: null
                    });

                    penalty_s_value = null;
                    penalty_e_value = null;
                }
            });
            // END บทลงโทษ

            //Penalty_QTY
            $.ajax({
                type: "GET",
                url: "/getdata/penaltyqty/" + emplid + "/" + corgroup_id,
                data: [{
                        "emplid": emplid
                    },
                    {
                        "corgroup_id": corgroup_id
                    },
                ],
                dataType: "JSON",
                success: function(response) {
                    var integer = parseInt(response[0].penalty_qty, 10);
                    $("#penalty_qty").val(integer + 1);

                }

            });
            //End Penalty QTY



        });
        // END สาเหตุความผิด    

        function getpenaltyqty() {
            $.ajax({
                type: "GET",
                url: "/getdata/penaltyqty/" + emplid + "/" + corgroup_id,
                data: [{
                        "emplid": emplid
                    },
                    {
                        "corgroup_id": corgroup_id
                    },
                ],
                dataType: "JSON",
                success: function(response) {
                    var integer = parseInt(response[0].penalty_qty, 10);
                    $("#penalty_qty").val(integer + 1);
                }

            });
        }
    });
</script>

@endsection