$(document).ready(function(){
	$('.typ').change(function(){
		
		$('#'+ $(this).attr('id').replace('type_', 'tclass_')).css('display','none');
		$('#'+ $(this).attr('id').replace('type_', 'tclass_')).prev('img').css('display','none');
		if($(this).val() == 'OTHER'){
			$(this).hide();
			$('#t'+ $(this).attr('id')).css('display','inline');
		}
		else{
			if($(this).val() == 'BELONGSTO' || $(this).val() == 'HASMANY'){

				$('#'+ $(this).attr('id').replace('type_', 'tclass_')).css('display','inline');
				$('#'+ $(this).attr('id').replace('type_', 'tclass_')).prev('img').css('display','inline');

				if($('#'+ $(this).attr('id').replace('type_', 'tclass_')).val().length == 0 ){
					$('#'+ $(this).attr('id').replace('type_', 'tclass_')).val($('#'+ $(this).attr('id').replace('type_', 'fieldattribute_')).val());					
				}
					
			}
		}
	});

	$('#wrapper').change(function(){
		if($(this).val() == 'PDO'){
			$('#disappear').hide();
			$('#PDOdriver').show();
		}
		else{
			$('#disappear').show();
			$('#PDOdriver').hide();	
		}
	});

	$('#FirstField').change(function(){
		$('#wrapper').empty();
		if($(this).val() == 'php5.1'){
			$('#wrapper').append($("<option></option>")
				         .attr("value",'PDO')
				         .text('PDO')); 
			$('#wrapper').append($("<option></option>")
				         .attr("value",'POG')
				         .text('POG')); 
		}
		else{
			$('#wrapper').append($("<option></option>")
				         .attr("value",'POG')
				         .text('POG')); 
		}
				
		$('#wrapper').change();
	});

	$('#submit').click(function(){
		return WarnMinInput(); 
	});

	$('.f').keydown(function(event){
		if(event.which == 40){
			var id = $(this).attr('id');
			var n = parseInt(id.replace('fieldattribute_',''));	
			var lastid = parseInt($('.line:hidden:first').attr('id').replace('line_', ''))-1;
			if(n==lastid) return;

			swap(n, n+1);
		}
		else{
			if(event.which == 38){
				var id = $(this).attr('id');
				var n = parseInt(id.replace('fieldattribute_',''));	
				if(n==1) return;

				swap(n, n-1);
			}
		}
	});
});

function swap(id1, id2){
	var this_attr = $('#fieldattribute_'+id1).val();
	var this_type = $('#fieldattribute_'+id1).siblings('select').val();
	var this_ttype = $('#ttype_'+id1).val();
	var this_class = $('#tclass_'+id1).val();

	var this_type_hidden = $('#fieldattribute_'+id1).siblings('select').css('display');
	var this_ttype_hidden = $('#ttype_'+id1).css('display');
	var this_class_hidden = $('#tclass_'+id1).css('display');

	console.log(this_type);

	$('#fieldattribute_'+id1).val($('#fieldattribute_'+id2).val());
	$('#fieldattribute_'+id1).siblings('select').val($('#fieldattribute_'+id2).siblings('select').val());
	$('#ttype_'+id1).val($('#ttype_'+id2).val());
	$('#tclass_'+id1).val($('#tclass_'+id2).val());

	$('#fieldattribute_'+id2).val(this_attr);
	$('#fieldattribute_'+id2).siblings('select').val(this_type);
	$('#ttype_'+id2).val(this_ttype);
	$('#tclass_'+id2).val(this_class);	

	$('#fieldattribute_'+id1).siblings('select').css('display', $('#fieldattribute_'+id2).siblings('select').css('display'));
	$('#ttype_'+id1).css('display', $('#ttype_'+id2).css('display'));
	$('#tclass_'+id1).css('display', $('#tclass_'+id2).css('display'));	
	$('#tclass_'+id1).prev('img').css('display',$('#tclass_'+id2).css('display'));

	$('#fieldattribute_'+id2).siblings('select').css('display', this_type_hidden);
	$('#ttype_'+id2).css('display', this_ttype_hidden);
	$('#tclass_'+id2).css('display', this_class_hidden);	
	$('#tclass_'+id2).prev('img').css('display',this_class_hidden);	

	$('#fieldattribute_'+id2).focus();

}


function AddField() {
	if($('.line:hidden:first').length>0)
 		$('#'+ $('.line:hidden:first').attr('id').replace('line_', 'attribute_')).css('display','block');
 }

 function ResetFields(){
 	$('input').val('');
 }

function WarnMinInput() {
    var inputCount = 0;
    var rtn=true;
    var allVals = new Array();
    var allCount = 0;

    $.each($('.f'), function(){
    	inputCount++;
    	if (this.value != '' && $.inArray(this.value.toLowerCase(), allVals) != -1 ) {
            alert("Warning:\nYou have more than 1 attribute with the same value. Attributes must be unique.");
            rtn=false;
            return false;
        } else if (this.value.toLowerCase() == $('#objName').val().toLowerCase()) {
            alert("An object cannot relate to itself recursively. Make sure attribute names are different from the object name.");
            rtn=false;
            return false;
        } else {
            allVals.push(this.value.toLowerCase());
            allCount++;
        }
    });

    if (inputCount > 0) {
    	/*
        var typeCount = 0;
        trs = document.getElementsByTagName("select");
        for (var w = 0; w < trs.length; w++) {
            if (trs[w].value == "HASMANY" || trs[w].value == "BELONGSTO" || trs[w].value == "JOIN") {
                typeCount++;
            }
        }
        if (typeCount >= inputCount) {
            alert("Warning:\nYou need to have at least 1 non-parent/child attribute. Else POG will generate an invalid PHP object");
        }
        */
    } else {
        alert("Warning:\nWithout any object attributes, POG may generate an invalid PHP object. You need to have at least 1 non-parent/child attribute");
        rtn=false;
        return false;
    }
   	return rtn;
}