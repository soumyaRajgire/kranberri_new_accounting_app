html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script>
        $(function () {
            $("#type").on("change", function () {
                var type = $('#type').find("option:selected").val();
                if (type.toUpperCase() == 'SELECT_MULTIPLE' || type.toUpperCase() == 'SELECT_ONE') {
                    $("#MyPopup").modal("show");
                } else {
                    $("#Div1").modal("show");
                }
            });
            $('[id*=btnClosePopup]').click('on', function () {
                $("#MyPopup").modal("hide");
            });
            $('[id*=Button1]').click('on', function () {
                $("#Div1").modal("hide");
            });
        });
    </script>
</head>
<body>
    <form id="form1" runat="server">
    <table class="table table-bordered table-striped table-highlight" id="tblTypes">
        <thead>
            <tr>
                <th>
                    Type*
                </th>
                <th>
                    Name*
                </th>
                <th>
                    Label*
                </th>
                <th>
                    Unit*
                </th>
                <th>
                </th>
                <th>
                    List_name*
                </th>
                <th>
                    choice_name*
                </th>
                <th>
                    choice_label*
                </th>
                <th>
                    &nbsp
                </th>
            </tr>
        </thead>
        <tbody>
            <input type="text" id="id" name="id" class="form-control hidden" />
            <input type="text" id="sop_id" name="sop_id" class="form-control hidden" />
            <tr>
                <td>
                    <select id="type" name="type" class="form-control">
                        <option></option>
                        <option>integer</option>
                        <option>decimal</option>
                        <option>select_one</option>
                        <option>select_multiple</option>
                        <option>barcode</option>
                        <option>image</option>
                        <option>video</option>
                        <option>date</option>
                        <option>string</option>
                        <option>geo_point</option>
                        <option>note</option>
                    </select>
                </td>
                <td>
                    <input type="text" id="name" name="name" class="form-control" value="" />
                </td>
                <td>
                    <input type="text" id="label" name="label" class="form-control" value="" />
                </td>
                <td>
                    <select id="unit" name="unit" class="form-control">
                        <option></option>
                        <option>kg</option>
                        <option>g</option>
                        <option>cm</option>
                        <option>cm2</option>
                        <option>cm3</option>
                        <option>mm</option>
                        <option>mm2</option>
                        <option>mm3</option>
                        <option>m</option>
                        <option>m2</option>
                        <option>m3</option>
                        <option>tonnnes</option>
                        <option>count</option>
                        <option>ha</option>
                        <option>acre</option>
                    </select>
                </td>
                <td>
                    <select id="category" name="category">
                        <option></option>
                        <option>Categorical</option>
                        <option>non_categorical</option>
                    </select>
                </td>
                <td>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" id="list_name" name="list" class="form-control" value="" />
                        </div>
                </td>
                <td>
                    <div class="form-group">
                        <input type="text" id="choice_name" name="variable" class="form-control" value="" />
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <input type="text" id="choice_list" name="choice" class="form-control" value="" />
                    </div>
                </td>
                <td>
                    <button type="button" id="addRec" class="btn btn-secondary btn-block" onclick="addVariable()">
                        Add
                    </button>
                    <button type="button" id="entry-update-btn" class="btn btn-sm btn-primary btn-block">
                        Update</button>
                    <button type="button" id="entry-update-cancel-btn" class="btn btn-sm btn-warning btn-block">
                        Cancel</button>
                </td>
            </tr>
        </tbody>
    </table>
    <div id="MyPopup" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <table class="table table-bordered table-striped" id="survey">
                    <thead>
                        <th>
                            Type*
                        </th>
                        <th>
                            Name*
                        </th>
                        <th>
                            Label*
                        </th>
                        <th>
                            Unit*
                        </th>
                    </thead>
                    <tbody>
                        <tr data-entry-id="survey">
                            <td class="type">
                            </td>
                            <td class="name">
                            </td>
                            <td class="label">
                            </td>
                            <td class="unit">
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="modal-footer">
                    <input type="button" id="btnClosePopup" value="Close" class="btn btn-danger" />
                </div>
            </div>
        </div>
    </div>
    <div id="Div1" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <table class="table table-bordered table-striped" id="choices">
                    <thead>
                        <th>
                            List_name*
                        </th>
                        <th>
                            choice_name*
                        </th>
                        <th>
                            choice_label*
                        </th>
                    </thead>
                    <tbody>
                        <tr data-entry-id="choices">
                            <td class="list_name">
                            </td>
                            <td class="choice_name">
                            </td>
                            <td class="choice_label">
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="modal-footer">
                    <input type="button" id="Button1" value="Close" class="btn btn-danger" />
                </div>
            </div>
        </div>
    </div>
    </form>
</body>
</html>