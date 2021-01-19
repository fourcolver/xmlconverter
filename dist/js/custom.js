
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})
$(function () {
    $('[data-toggle="popover"]').popover()
}) 

$(document).on('change', 'input[type=radio][name^=tagRadio]', function() {
    $(this).closest('td').find('button').text(this.value);
});

// remove xml information
$(document).on('click', '.removeXml', function() {
    let data_id = $(this).attr("data-id");
    $("#removeId").val(data_id);
})

// running xml information
$(document).on('click', '.runningXml', function() {
    let data_id = $(this).attr("data-id");
    $("#runningId").val(data_id);
})

//remove modal handling
$(".removeItem").click(function(){
    let data_id = $("#removeId").val();
    $.ajax({
        url: "parsexml.php",
        type: "post",
        dataType: "json",
        data: {"removeItem": "valid", "data_id": data_id},
        success: function(result) {
            console.log(result);
            if(result) {
                location.reload();
            }
        }
    })
})

// running modal handling
$(".runningItem").click(function(){
    let data_id = $("#runningId").val();
    $.ajax({
        url: "parsexml.php",
        type: "post",
        dataType: "json",
        data: {"runningItem": "valid", "data_id": data_id},
        success: function(result) {
            console.log(result);
            if(result.data == "true") {
                $('#runningModal').modal('toggle');
                $("#confirmModal").modal('toggle');
            }
            else if(result.data == "warning") {
                alert('This is already in progress');
            }
            else {
                alert("Something went wrong while processing");
            }
        }
    })
})

// Confirm modal handling
$(".confirmItem").click(function() {
    location.reload();
});

// update xml 
$("#updateDetail").click(function(){
    let willAddCountry = "invalid";
    if ($('#willCountryCheck').is(":checked"))
    {
        willAddCountry = $('#willEditCountry').val();
    }
    let jobLocationType = "invalid";
    if ($('#willLocationCheck').is(":checked"))
    {
        jobLocationType = $('#jobLocationType').val();
    }
    let id = $("#xmlid").val();
    let feedName = $("#feedName").val();
    let xmlurl = $("#xmlurl").val();
    let updatetag = "";
    $( "tbody#parsing input[type=radio][name^=tagRadio]:checked" ).each(function( index ) {
        updatetag += `${this.value},`;
    });
    if(feedName == "" || xmlurl == "" || willAddCountry == "") {
        alert("Fill form values");
    }
    else {
        $.ajax({
            url: "parsexml.php",
            type: "post",
            dataType: "json",
            data: {"updateFeed": "valid", "id": id, "feedName": feedName, "xmlurl": xmlurl, "updatetag": updatetag, "willAddCountry": willAddCountry, "jobLocationType": jobLocationType},
            success: function(result) {
                console.log(result);
                if(result.data == "true") {
                    window.location.href = 'managefeeds.php';
                }
                else {
                    alert('Something went wrong while saving feed details');
                }
            }
        })
    }
})

$("#saveDetail").click(function(){
    let willAddCountry = "invalid";
    if ($('#willCountryCheck').is(":checked"))
    {
        willAddCountry = $('#willAddCountry').val();
    }
    let jobLocationType = "invalid";
    if ($('#willLocationCheck').is(":checked"))
    {
        jobLocationType = $('#jobLocationType').val();
    }
    console.log(jobLocationType);
    let feedName = $('#feedName').val();
    let xmlurl = $('#xmlurlHidden').val();
    let basetag = $("#baseTagValue").val();
    let updatetag = "";
    let cdatatag = $("#cdataTag").val();
    $( "tbody#parsing input[type=radio][name^=tagRadio]:checked" ).each(function( index ) {
        updatetag += `${this.value},`;
    });
    if(feedName == "" || xmlurl == "" || willAddCountry == "") {
        alert("Fill form values");
    }
    else {
        $.ajax({
            url: "parsexml.php",
            type: "post",
            dataType: "json",
            data: {"saveFeed": "valid", "feedName": feedName, "xmlurl": xmlurl, "basetag": basetag, "updatetag": updatetag, "cdatatag": cdatatag, "willAddCountry": willAddCountry, "jobLocationType": jobLocationType},
            success: function(result) {
                if(result.data == "true") {
                    window.location.href = 'managefeeds.php';
                }
                else {
                    alert('Something went wrong while saving feed details');
                }
            }
        })
    }
})


//add country pre tag
$("#willCountryCheck").click(function(){
    $("#willAddCountry").toggle(200);
})

//update country pre tag
$("#willCountryCheck").click(function(){
    $("#willEditCountry").toggle(200);
})

//add jobLocation tag
$("#willLocationCheck").click(function(){
    $("#jobLocationType").toggle(200);
})

$(document).ready(function() {
    $('#feedinfo').DataTable({
         "aoColumns": [
            null,
            null,
            null,
            null,
            null,
            null,
            { "bSortable": false }
         ],
         "pageLength": 50,
        //  "columns": [
        //     { "width": "10%" },
        //     { "width": "20%" },
        //     { "width": "30%" },
        //     { "width": "10%" },
        //     { "width": "10%" },
        //     { "width": "10%" },
        //     { "width": "5%" }
        // ],
        // "autoWidth":false,
    });
} );

$("#parseXML").click(function(){

    let xmlurl = $("#xmlurl").val()
    if(xmlurl == '')  {
        alert("Please enter the xmlURL");
    }

    else {
        $(".container-fluid").LoadingOverlay("show", {
            background  : "rgba(165, 190, 100, 0.5)"
        });
        $.ajax({
            url:"parsexml.php",
            type: "post",
            dataType: 'json',
            data: {parse: "valid", url: xmlurl},
            success:function(result){
                $('#parsing').html("");

                $(".container-fluid").LoadingOverlay("hide", true);
                if(result == false) {
                    alert("Please Check the job xml url");
                }
                else {
                    let mainString = '';
                    let baseTag = result.baseTag;
                    let baseTagValue = '';
                    let baseValue = result.baseValue;
                    let cdataTag = result.cdataTag;
                    let cdatTagValue = '';
                    for(i = 0; i < cdataTag.length; i++) {
                        cdatTagValue += `${cdataTag[i]},`;
                    }
                    for(i = 0; i < baseTag.length; i++) {
                        baseTagValue += `${baseTag[i]},`;
                    }
                    $("#xmlurlHidden").val(xmlurl);
                    $("#cdataTag").val(cdatTagValue);
                    $("#baseTagValue").val(baseTagValue);
                    for(i = 0; i < baseTag.length; i++) {
                        mainString += `
                        <tr>
                            <td class="align-middle"><strong> &lt;${baseTag[i]}&gt;</strong></td>
                            <td class="align-middle text" data-container="body" data-toggle="popover" data-placement="top" data-content="rices."><span><i class="fas fa-eye"></i>${baseValue[i]}</span></td>
                            <td align="right" class="" style="width: 15%;">
                                <div class="dropdown">
                                    <button class="btn btn-default  dropdown-toggle btn-block" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Default
                                    </button>
                                    <div class="dropdown-menu dropdown-status-tag dropdown-menu-right p-4" aria-labelledby="dropdownMenuButton">
                                        <div class="row mx-sm-n1">
                                            <div class="col-md">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="tagRadio_${i}" id="labelRadio_${i}_1" value="Default" checked>
                                                    <label class="form-check-label" for="labelRadio_${i}_1">
                                                    &lt;Default&gt;
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="tagRadio_${i}" id="labelRadio_${i}_2" value="id">
                                                    <label class="form-check-label" for="labelRadio_${i}_2">
                                                    &lt;id&gt;
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="tagRadio_${i}" id="labelRadio_${i}_3" value="title">
                                                    <label class="form-check-label" for="labelRadio_${i}_3">
                                                    &lt;title&gt;
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="tagRadio_${i}" id="labelRadio_${i}_4" value="company">
                                                    <label class="form-check-label" for="labelRadio_${i}_4">
                                                    &lt;company&gt;
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="tagRadio_${i}" id="labelRadio_${i}_5" value="addressCountry">
                                                    <label class="form-check-label" for="labelRadio_${i}_5">
                                                    &lt;addressCountry&gt;
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="tagRadio_${i}" id="labelRadio_${i}_6" value="city">
                                                    <label class="form-check-label" for="labelRadio_${i}_6">
                                                    &lt;city&gt;
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="tagRadio_${i}" id="labelRadio_${i}_26" value="addressRegion">
                                                    <label class="form-check-label" for="labelRadio_${i}_26">
                                                    &lt;addressRegion&gt;
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="tagRadio_${i}" id="labelRadio_${i}_7" value="geonameId">
                                                    <label class="form-check-label" for="labelRadio_${i}_7">
                                                    &lt;geonameId&gt;
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="tagRadio_${i}" id="labelRadio_${i}_8" value="geonameLocality">
                                                    <label class="form-check-label" for="labelRadio_${i}_8">
                                                    &lt;geonameLocality&gt;
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="tagRadio_${i}" id="labelRadio_${i}_9" value="geonameLongitude">
                                                    <label class="form-check-label" for="labelRadio_${i}_9">
                                                    &lt;geonameLongitude&gt;
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="tagRadio_${i}" id="labelRadio_${i}_10" value="geonameLatitude">
                                                    <label class="form-check-label" for="labelRadio_${i}_10">
                                                    &lt;geonameLatitude&gt;
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="tagRadio_${i}" id="labelRadio_${i}_11"  value="content">
                                                    <label class="form-check-label" for="labelRadio_${i}_11">
                                                    &lt;content&gt;
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="tagRadio_${i}" id="labelRadio_${i}_12" value="datePosted">
                                                    <label class="form-check-label" for="labelRadio_${i}_12">
                                                    &lt;datePosted&gt;
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="tagRadio_${i}" id="labelRadio_${i}_14" value="url">
                                                    <label class="form-check-label" for="labelRadio_${i}_14">
                                                    &lt;url&gt;
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="tagRadio_${i}" id="labelRadio_${i}_15" value="contractType">
                                                    <label class="form-check-label" for="labelRadio_${i}_15">
                                                    &lt;contractType&gt;
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="tagRadio_${i}" id="labelRadio_${i}_16" value="remotePolicy">
                                                    <label class="form-check-label" for="labelRadio_${i}_16">
                                                    &lt;remotePolicy&gt;
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="tagRadio_${i}" id="labelRadio_${i}_17" value="employmentType">
                                                    <label class="form-check-label" for="labelRadio_${i}_17">
                                                    &lt;employmentType&gt;
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="tagRadio_${i}" id="labelRadio_${i}_18" value="salaryCurrency">
                                                    <label class="form-check-label" for="labelRadio_${i}_18">
                                                    &lt;salaryCurrency&gt;
                                                    </label>
                                                </div>                                                
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="tagRadio_${i}" id="labelRadio_${i}_19" value="industry">
                                                    <label class="form-check-label" for="labelRadio_${i}_19">
                                                    &lt;industry&gt;
                                                    </label>
                                                </div>                                              
                                            </div>
                                            <div class="col-md">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="tagRadio_${i}" id="labelRadio_${i}_20" value="estimatedSalary">
                                                    <label class="form-check-label" for="labelRadio_${i}_20">
                                                    &lt;estimatedSalary&gt;
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="tagRadio_${i}" id="labelRadio_${i}_21" value="validThrough">
                                                    <label class="form-check-label" for="labelRadio_${i}_21">
                                                    &lt;validThrough&gt;
                                                    </label>
                                                </div>                                                
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="tagRadio_${i}" id="labelRadio_${i}_22" value="hiringOrganization">
                                                    <label class="form-check-label" for="labelRadio_${i}_22">
                                                    &lt;hiringOrganization&gt;
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="tagRadio_${i}" id="labelRadio_${i}_23" value="occupationalCategory">
                                                    <label class="form-check-label" for="labelRadio_${i}_23">
                                                    &lt;occupationalCategory&gt;
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="tagRadio_${i}" id="labelRadio_${i}_24" value="logoUrl">
                                                    <label class="form-check-label" for="labelRadio_${i}_24">
                                                    &lt;logoUrl&gt;
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="tagRadio_${i}" id="labelRadio_${i}_25" value="discard">
                                                    <label class="form-check-label" for="labelRadio_${i}_25">
                                                    Discard
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <div>
                            </td>
                        </tr>
                    `;
                    }
                    
                    $('#parsing').append(mainString);
                    $('#tagNumber').text(`${baseTag.length} results to map:`)
                }
            }
        });
    }
});