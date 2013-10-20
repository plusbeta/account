/* ================================== */
/* Event                              */
/* ================================== */
function confirmEvent(e,n){
	var msg= {'copy' : '複写してよろしいですか？',
			  'delete' : '本当に削除しますか？'};
		if(confirm(msg[e])){
			location.href='/sys/accounts/'+e+'/'+n;
		}
}
function confirmClient(e,n){
	var msg= {'copy' : '複写してよろしいですか？',
			  'delete' : '本当に削除しますか？'};
		if(confirm(msg[e])){
			location.href='/sys/clients/'+e+'/'+n;
		}
}
function dispSentence(){
	function getSentence(data){
		var offset=$("#selSentence").offset();

		$("#dispSentence").css({
					visibility: 'visible',
					top: offset.top + 'px',
					left: offset.left + 'px'
							});
	}
 	$.getJSON('/sys/settings/getlist',null,getSentence);

}
function submitBank(m,i){
	$('#reg_mode').val(m);
	if(i){
		$('#reg_id').val(i);
		}
	document.bank.submit();
}
function addPersonRow(){
// 明細行の追加
	var td;
	var tr = $("<tr>");

	var $rows = $("#person").children().children();
	var len = $rows.length-1;
	

	td='<td><input name="data[Client_person]['+len+'][id]" type="hidden" value="" /><input name="data[Client_person]['+len+'][client_id]" type="hidden" value="'+$("#ClientId").val()+'" />';
	td+='<input name="data[Client_person]['+len+'][devision]" type="text" value="" size="20" maxlength="50" id="Item'+len+'Devision" /></td>';
    tr.append(td);

	td='<td><input name="data[Client_person]['+len+'][name]" type="text" value="" size="20" maxlength="50" id="Item'+len+'Name" /></td>';
    tr.append(td);

	td='<td><input name="data[Client_person]['+len+'][kana]" type="text" value="" size="20" maxlength="50" id="Item'+len+'kana" /></td>';
    tr.append(td);

	td='<td><input name="data[Client_person]['+len+'][title]" type="text" value="" size="20" maxlength="50" id="Item'+len+'Title" /></td>';
    tr.append(td);

	td='<td><input name="data[Client_person]['+len+'][email]" type="text" value="" size="20" maxlength="255" id="Item'+len+'Email" class="amount" /></td>';
    tr.append(td);

	td='<td><input name="data[Client_person]['+len+'][tel]" type="text" value="" size="20" maxlength="15" id="Item'+len+'Tel" class="amount" /></td>';
    tr.append(td);
    
    $("#person").append(tr);

}
/* ================================== */
/* Initialize                         */
/* ================================== */
// 画面初期処理
$(function(){
	var $rows = $("#item").children().children();
	var len = $rows.length;
	
	$rows.children().keydown(function(event){
		calc(event);
	});
	$rows.children().keyup(function(event){
		calc(event);
	});
	calcTotal();

	// 顧客担当
	if($("#AccountClientId").val()!=""){
		getClientPeople($("#AccountClientId").val(),$("#AccountCreated").val());
	}
	// 口座登録
	if($("#reg_id").val()!=""){
		$("#submitBtn").val("更新する");
	}
	//検索ボックスサジェスト
	$("#searchText").autocomplete("/sys/accounts/sglist",
		{
			onItemSelect:searchItem
		});


	$("#searchYear").change(function(){
		if($(this).val()==""){
			$("#searchMonth").val("");
		}
		var word=$("#searchText").val();
		getItem(word);
		//getPagenate();
	});
	$("#searchMonth").change(function(){
		if($("#searchYear").val()==""){
			alert("年を選択してください");
			$("#searchYear").focus();
			return;
		}
		var word=$("#searchText").val();
		getItem(word);
		//getPagenate();
	});
	$("#searchFlag").change(function(){
		var word=$("#searchText").val();
		getItem(word);
		//getPagenate();
	});
	//顧客名サジェスト
	$("#clientname").autocomplete("/sys/clients/sglist",
		{
			onItemSelect:selectItem
		}); 
	$("#clientname").keydown(function(event){
		$("this").autocomplete("/sys/clients/sglist",
			{
				onItemSelect:selectItem
		}); 
	});


	$("#divBox").change(function(){
		$("#memBox").val($(this).val());
	
	});
	$("#memBox").change(function(){
		$("#divBox").val($(this).val());
	
	});
	$("#tax").change(function(){
		var cnt=0;
		for(i=0;i<17;i++){
			cnt=cnt+Number($("#Item"+i+"Amount").val());
		}		
		$("#Item17Content").val("管理進行費（"+roundData($(this).val()*100,1)+"%）");
		$("#Item17Amount").val(roundData(cnt*$(this).val(),1));
		calcTotal();

	});
	
	$("#selSentence").click(function(){
		function getSentence(data){
			$("#dispSentence ul").empty();
		    $.each(data,function(i){
	    		$("#dispSentence ul").append("<li>"+data[i]['sentence']+"</li>").css("cursor","hand");
		    });
	    	
			$("#dispSentence ul li").bind("click", function(){
			  $("#AccountTerms2").text($(this).text());
			});
			$("#dispSentence ul li").hover(
				 function(){$("#dispSentence ul li").css({cursor:"hand"});},
				 function(){$("#dispSentence ul li").css({cursor:"arrow"});}
			);
			var offset=$("#selSentence").offset();
			$("#dispSentence").css({
						visibility: 'visible',
						top: (offset.top +20)+ 'px',
						left: offset.left + 'px'
								});
		}
	 	$.getJSON('/sys/settings/getlist',null,getSentence);
	
	});

	$("#dispSentence a").click(function(){
		$("#dispSentence").css({
					visibility: 'hidden'
					});
	});

	// ステータスの更新
	$(".flags").change(function(){
		$.ajax({
			type: "POST",
			url:  "/sys/accounts/chgStatus",
			data: "id="+$(this).attr("target")+"&flg="+$(this).attr("name")+"&sts="+$(this).val(),
			dataType:"json",
			success: function(msg){
				//$('#output_path').html('<a href="'+msg+'" target="_blank">'+msg+'</a> にファイルを出力しました');
			},
			error: function (data, s, e) {
				alert('Sorry, an error occured.');
			}
		});
	});
	
	$(document).keydown(function(event){
		pressKey=event.keyCode;   
     	if(pressKey==13){return false;} 
	});
	
});
function getClientPeople(s,c){
	function clientUpdate(data){
		$("#AccountAccountNoT").text(data['no_t']);
		
		var $pulldown = $("#divBox");
	    $pulldown.empty();
	    $option_entries = new Array();
	    $option_entries.push('<option value="">選択してください</option>');
	    $.each(data['div'],function(i){
	      $option_entries.push('<option value="' + data['div'][i][0] + '">' + data['div'][i][1] + '</option>');
	    });
	    $pulldown.append($option_entries.join());
		
		var $pulldown = $("#memBox");
	    $pulldown.empty();
	    $option_entries = new Array();
	    $option_entries.push('<option value="">選択してください</option>');
	    $.each(data['mem'],function(i){
	      $option_entries.push('<option value="' + data['mem'][i][0] + '">' + data['mem'][i][1] + '</option>');
	    });
	    $pulldown.append($option_entries.join());

		if($("#client_people_id").val()!=""){
			$("#divBox").val($("#client_people_id").val());
			$("#memBox").val($("#client_people_id").val());
		}
	}
 	$.getJSON('/sys/accounts/anum/',{id:s,date:c},clientUpdate);
}
function selectItem(li){
	var sValue1 = li.extra[0]; //code
	var sValue2 = li.extra[1]; //id

	$("#AccountClientId").val(sValue2);
	var cdate=$("#AccountCreated").val();

	getClientPeople(sValue2,cdate);

	
}
function getItem(word){
	var year=$("#searchYear").val();
	var month=$("#searchMonth").val();
	var status=$("#searchFlag").val();
	if(word==''){
		word='all';
	}
	function getList(data){
		//alert(data);
		$("#account_list tr.a_row").remove();
		$("#cnt").text(data.length);
	    $.each(data,function(i){
	    	row='<tr class="a_row">';
	    	row=row+'<td>'+data[i].created+'</td>';
	    	row=row+'<td>'+data[i].bill_date+'</td>';
	    	row=row+'<td><a href="/sys/accounts/view/'+data[i].id+'">'+data[i].name+'</a></td>';
	    	row=row+'<td>'+data[i].cname+'</td>';
	    	row=row+'<td class="number">'+'￥ '+data[i].contract_price+'</td>';
	    	row=row+'<td>'+data[i].mname+'</td>';
	    	row=row+'<td>'+data[i].e_flg+'&nbsp;&nbsp;'+data[i].b_flg+'&nbsp;&nbsp;'+data[i].d_flg+'&nbsp;&nbsp;'+data[i].r_flg+'&nbsp;&nbsp;</td>';
	    	row=row+'</tr>';
	      $("#account_list").append(row);
	    });
getPagenate();
	}

 	$.getJSON('/sys/accounts/getlist',{w:word,y:year,m:month,s:status},getList);

}
function getPagenate(){

 	$.get('/sys/accounts/dispPage',function(data){
 		$("#pagenate").html(data);
 	
 	});
	
}
function searchItem(li){
	var word=li.selectValue;
	getItem(word);
	

}

/* ================================== */
/* Sub Routine                        */
/* ================================== */
// 明細行の計算追加
function calc(event){
		var target=event.target;
		//if((event.which==13)||(event.which==9)){
			if(target.id.indexOf("Price")>0){
				num=document.getElementById(target.id.replace("UnitPrice","Number"));
				amt=document.getElementById(target.id.replace("UnitPrice","Amount"));
				amt.value=num.value*target.value;
			}
			if(target.id.indexOf("Number")>0){
				pri=document.getElementById(target.id.replace("Number","UnitPrice"));
				amt=document.getElementById(target.id.replace("Number","Amount"));
				amt.value=pri.value*target.value;
			}
			calcTotal();
		//}

}
// 明細行の合計計算
function calcTotal(){
	var cnt=0;

	$(".amount").each(function(){
		cnt=cnt+Number($(this).val());
		});
		
		var tax=roundData(cnt*0.05,1)
		$("#subTotal").val(cnt);
		$("#Tax").val(tax);
		$("#Total").val(cnt+tax);
		$("#AccountContractPrice").val(cnt+tax);

}

// 四捨五入
// d: 元の数字
// n: 桁数  1(整数) 10(小数点第一位) 100（第二位）
function roundData(d,n){
	var rc=0;
	rc=d*n;
	rc=Math.round(rc);
	rc=rc/n;
	return rc;
}