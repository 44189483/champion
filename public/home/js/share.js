/*
* 分享JS
*/
$(function(){
	//分享按钮
	$(".share").click(function(){
		//固定高度禁下拉
		$("body").attr("style","overflow:hidden");  
		$(".fenxiang").css("height",$(document).height()); 
		$(".fenxiang").show();
	});

	$(".fenxiang-3").click(function(){
		$(".fenxiang").hide();
		$("body").attr("style","");
	});

});

/*
* shareTo 分享按钮
* stype   类型
* title   标题 
* pic	  图片
* content 内容
* mid     会员ID 
* id      文章ID
* type    所有栏目
*/
function shareTo(stype,title,pic,content,mid,id,type){

    if(pic == ''){
        pic = 'http://101.37.107.121/public/home/images/logo.png';
    }else{
    	pic = 'http://101.37.107.121/'+pic;
    }

    //新浪微博接口的传参
    if(stype=='sina'){

        if(android){
            android.shareFromSina(document.location.href,title,content,pic,mid,id,type);
        }

    }

    //生成二维码给微信扫描分享
    if(stype == 'wechat'){

        if(android){
            android.shareFromWechat(document.location.href,title,content,pic,mid,id,type);
        }

    }

    //qq空间接口的传参
    if(stype=='qzone'){

        if(android){
            android.shareFromQzone(document.location.href,title,content,pic,mid,id,type);
        }

    }
      
    //qq好友接口的传参
    if(stype == 'qq'){

        if(android){
            android.shareFormQQ(document.location.href,title,content,pic,mid,id,type);
        }

    }
      
}
