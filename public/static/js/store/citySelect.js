$.fn.sjld = function(shenfen,chengshi,quyu){
    $.ajax({
        type: 'GET',
        url: '/Admin/store/cityPicker',
        dataType: 'json',
        success: function(res){
            var sfp = shenfen+' p'
            var csp = chengshi+' p'
            var qyp = quyu+' p'
            var sfli = shenfen+' ul li'
            var csli = chengshi+' ul li'
            var qyli = quyu+' ul li'
            
            var sfgsmr = res.data; // 省

            // 判断是否选择了省份
            if ($(shenfen).find('p').text() == '请选择')  {
                $('#chengshi').find('p').css('color','#999');
                $('#quyu').find('p').css('color','#999');
            }

            // 省份列表
            for(a=0;a<sfgsmr.length;a++){
                var sfmcmr = sfgsmr[a].name;
                var sfnrmr = "<li data-id='"+ sfgsmr[a].id +"'>"+sfmcmr+"</li>";
                $(shenfen).find('ul').append(sfnrmr);
            }
            
            /****----区域选择----****/
            // 省级选择
            $(sfli).on('click',function(event){
                var dqsf = $(this).text();
                var provinceId = $(this).attr('data-id');
                $(this).addClass('selected').siblings().removeClass('selected'); // 当前项selected
                $(shenfen).find('p').text(dqsf);
                $(shenfen).find('p').attr('title',dqsf);
                var sfnum = $(this).index(); // 当前选择项
                
                // p标签text颜色
                if(dqsf != '请选择') {
                    $('#chengshi').find('p').css('color','#333')
                }

                var csgs = res.data[sfnum].children;  // 当前选择项的下一级
                $(chengshi).find('ul').text('');
                // 添加城市列表
                for(i=0;i<csgs.length;i++){
                    var csmc = csgs[i].name;
                    var csnr = "<li data-id='"+ csgs[i].id +"'>"+csmc+"</li>";
                    $(chengshi).find('ul').append(csnr);
                }		
                $(csp).text('请选择');
                $(qyp).text('请选择');
                // $(this).parent().parent().slideUp(200);
                $("#chengshi .m_zlxg2").slideDown(200);
                $('#sfdq_num').val(sfnum);
                $('.apply_region_txt').attr('province-id',provinceId); // 省份id
                event.stopPropagation();    //  阻止事件冒泡

                // 市级选择
                $(csli).on('click',function(event){
                    var dqcs = $(this).text();
                    var cityId = $(this).attr('data-id');
                    // p标签text颜色
                    if(dqcs != '请选择') {
                        $('#quyu').find('p').css('color','#333')
                    }
                    var dqsf_num = $('#sfdq_num').val();
                    if(dqsf_num==""){
                        dqsf_num=0;
                    }else{
                        var dqsf_num = $('#sfdq_num').val();
                    }
                    $(this).addClass('selected').siblings().removeClass('selected'); // 当前项selected
                    $(chengshi).find('p').text(dqcs);
                    $(chengshi).find('p').attr('title',dqcs);
                    var csnum = $(this).index();
                    var qygs = res.data[dqsf_num].children[csnum].children; // 当前选择项的下一级
                    $(quyu).find('ul').text('');
                    // 添加区、县列表
                    for(j=0;j<qygs.length;j++){
                        var qymc = qygs[j].name;
                        var qynr = '<li data-id="'+ qygs[j].id +'">'+qymc+'</li>'; //id
                        $(quyu).find('ul').append(qynr);
                    }
                    $(qyp).text('请选择');
                    $('#csdq_num').val(csnum);
                    $('.apply_region_txt').attr('city-id',cityId); // 城市id
                    // $(this).parent().parent().slideUp(200);
                    $("#quyu .m_zlxg2").slideDown(200);
                    event.stopPropagation();    //  阻止事件冒泡
                
                    // 区级选择
                    $(qyli).on('click',function(event){
                        var dqqy = $(this).text();
                        var districtId = $(this).attr('data-id');
                        $(this).addClass('selected').siblings().removeClass('selected'); // 当前项selected
                        $(quyu).find('p').text(dqqy).css('color','#333');
                        $(quyu).find('p').attr('title',dqqy);
                        // $(this).parent().parent().slideUp(200);
                        event.stopPropagation();    //  阻止事件冒泡
                        $('.apply_region_txt').attr('district-id',districtId); // 区、县id
                    }) 
                })	
            })

            // 判断是否有省份和点击选择
            $('#shenfen').on('click',function(){
                // $(this).find('.m_zlxg2').slideDown(200);
            })

            $('#chengshi').on('click',function(){
                if($('#shenfen').find('p').text() == '请选择'){
                    $(this).find('.m_zlxg2').slideUp(200);
                    return;
                }else{
                    $(this).find('.m_zlxg2').slideDown(200);
                }
            })

            $('#quyu').on('click',function(){
                if($('#shenfen').find('p').text() == '请选择' && $('#chengshi').find('p').text() == '请选择'){
                    $(this).find('.m_zlxg2').slideUp(200);
                    return;
                }else{
                    $(this).find('.m_zlxg2').slideDown(200);
                }
            })

            // $(document).on('click',function(event){
            //     $('.m_zlxg2').slideUp(200);
            //     event.stopPropagation();
            // })
        },
        error: function(err){
            console.log(err);
        }
    })
}