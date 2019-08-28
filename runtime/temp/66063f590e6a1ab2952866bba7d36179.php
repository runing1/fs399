<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:48:"./application/admin/view/store\publishGoods.html";i:1565603977;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>发布商品</title>
    <link href="/public/static/css/main.css" rel="stylesheet" type="text/css">
    <link href="/public/static/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css">
    <link href="/public/static/font/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="/public/static/css/store_apply.css" rel="stylesheet" type="text/css">
    <link href="/public/static/js/layui/css/layui.css" rel="stylesheet" type="text/css">
    <link href="/public/static/css/layer_open.css" rel="stylesheet" type="text/css"/><!--layer.open弹框样式-->
    <script type="text/javascript" src="/public/static/js/jquery.js"></script>
    <script type="text/javascript" src="/public/static/js/jquery.cookie.js"></script>
    <script type="text/javascript" src="/public/static/js/jquery.validation.min.js"></script>
    <script type="text/javascript" src="/public/static/js/layer/layer.js"></script><!--弹窗js 参考文档 http://layer.layui.com/--> 
    <script type="text/javascript" src="/public/static/js/layui/layui.js"></script>
    <style type="text/css">
        body {
            background: #f5f5f5;
            font-size: 14px;
            overflow: scroll;
            color: #666;
        }
    </style>
</head>
<body>
    <div id="apply_store">
        <!--head-->
        <div id="apply_store_head">
            <!-- <div class="apply_top_bar">
                <div class="back_btn"><</div>
                <h2 class="title">申请入驻</h2>
            </div> -->
            <div class="apply_logo">
                <p class="left_content logo_txt">头像LOGO</p>
                <div class="right_content logo_content choose_upload">
                    <input type="hidden" class="logo_hidden" value="">
                    <img src="" alt="">
                    <span>></span>
                    <div class="upload_file" id="logo_file"></div>
                </div>
            </div>
        </div>
        <div id="apply_store_main">
            <!--店铺信息-->
            <div class="main_store_info">
                <div class="store_name apply_store_item">
                    <p class="left_content store_name_txt">商家名称</p>
                    <div class="right_content store_name_content">
                        <input class="apply_store_input store_name_input" name="store_name" maxlength="15" type="text" placeholder="请输入商家名称">
                    </div>
                </div>
                <div class="apply_attribute apply_store_item">
                    <p class="left_content attribute_txt">入驻属性</p>
                    <div class="right_content apply_attribute_content" style="cursor: pointer;">
                        <span class="apply_attribute_txt">请选择商家属性</span>
                        <span class="arrow">></span>
                    </div>
                </div>
                <div class="apply_category apply_store_item" style="display: none;">
                    <p class="left_content category_txt">店铺类别</p>
                    <div class="right_content apply_category_content" style="cursor: pointer;">
                        <span class="apply_category_txt">请选择店铺类别</span>
                        <span class="arrow">></span>
                    </div>
                </div>
                <div class="apply_region apply_store_item">
                    <p class="left_content region_txt">所在地区</p>
                    <div class="right_content apply_region_content" style="cursor: pointer;">
                        <span class="apply_region_txt">请选择省市区</span>
                        <span class="arrow">></span>
                    </div>
                </div>
                <div class="apply_street apply_store_item">
                    <p class="left_content street_txt">选择街道</p>
                    <div class="right_content apply_street_content" style="cursor: pointer;">
                        <span class="apply_street_txt">请选择街道</span>
                        <span class="arrow">></span>
                    </div>
                </div>
                <div class="apply_QQ apply_store_item">
                    <p class="left_content QQ_txt">店家QQ</p>
                    <div class="right_content apply_QQ_content">
                        <input class="apply_store_input apply_QQ_input" maxlength="12" type="text" placeholder="请输入QQ号">
                    </div>
                </div>
                <div class="apply_wechat apply_store_item">
                    <p class="left_content wechat_txt">店家微信</p>
                    <div class="right_content apply_wechat_content">
                        <input class="apply_store_input apply_wechat_input" maxlength="20" type="text" placeholder="请输入微信号">
                    </div>
                </div>
            </div>
            <!--微信二维码-->
            <div class="main_wechat_code">
                <div class="left_text">
                    <p>上传微信二维码</p>
                    <span>我的-个人信息-我的二维码<br/>截图上传至平台</span>
                </div>
                <div class="right_pic choose_upload">
                    <input type="hidden" class="code_hidden" value="">
                    <img src="" alt="">
                    <div class="upload_file" id="code_file"></div>
                </div>
            </div>
            <!--个人信息-->
            <div class="main_personal_info">
                <div class="real_name apply_store_item">
                    <p class="left_content real_name_txt">真实姓名</p>
                    <div class="right_content real_name_content">
                        <input class="apply_store_input real_name_input" maxlength="12" type="text" placeholder="请输入真实姓名">
                    </div>
                </div>
                <div class="id_card apply_store_item">
                    <p class="left_content id_card_txt">身份证</p>
                    <div class="right_content id_card_content">
                        <input class="apply_store_input id_card_input" maxlength="18" type="text" placeholder="请输入本人身份证号">
                    </div>
                </div>
                <div class="bank_card apply_store_item">
                    <p class="left_content bank_card_txt">银行卡号</p>
                    <div class="right_content bank_card_content">
                        <input class="apply_store_input bank_card_input" maxlength="20" type="text" placeholder="请输入本人绑定卡号">
                    </div>
                </div>
                <div class="mobile_phone apply_store_item">
                    <p class="left_content mobile_phone_txt">手机号码</p>
                    <div class="right_content mobile_phone_content">
                        <input class="apply_store_input mobile_phone_input" maxlength="11" type="text" placeholder="请输入手机号码">
                    </div>
                </div>
                <div class="validation_code apply_store_item">
                    <p class="left_content validation_code_txt">验证码</p>
                    <div class="right_content validation_code_content">
                        <input style="margin-right:10px;" class="apply_store_input validation_code_input" maxlength="8" type="text" placeholder="请输入验证码">
                        <input type="button" class="validation_code_btn" value="获取验证码" style="cursor:pointer;"/>
                    </div>
                </div>
            </div>
            <!--身份证正反面-->
            <div class="id_card_info">
                <div class="card_info_item card_info_positive">
                    <p class="tit">身份证正面照</p>
                    <span class="msg">信息无覆盖 内容清晰</span>
                    <div class="card_info_img positive_img choose_upload">
                        <input type="hidden" class="positive_hidden" value="">
                        <img src="" alt="">
                        <div class="upload_file" id="positive_card_file"></div>
                    </div>
                </div>
                <div class="card_info_item card_info_reverse">
                    <p class="tit">身份证反面照</p>
                    <span class="msg">信息无覆盖 内容清晰</span>
                    <div class="card_info_img reverse_img choose_upload">
                        <input type="hidden" class="reverse_hidden" value="">
                        <img src="" alt="">
                        <div class="upload_file" id="reverse_card_file"></div>
                    </div>
                </div>
            </div>
            <!--提交-->
            <div class="submit_approve">
                <div class="submit_btn">提交认证</div>
                <div class="agreement">
                    <input type="checkbox" name="favorite" />
                    <p>勾选即代表您已阅读并同意<span>《商家入驻协议》</span></p>
                </div>
            </div>
        </div>
    </div>
</body>
<!--商家属性-->
<div id="apply_attribute">
    <div class="attribute_item online_store" type="onlin">
        <p>线上商家</p>
        <span class="selected"></span>
    </div>
    <div class="attribute_item offline_store" type="offlin">
        <p>线下商家</p>
        <span></span>
    </div>
</div>
<!--店铺类别-->
<div id="apply_category">
    <div class="category_content">
        <div class="one_bar">
            <ul class="one_bar_ul"></ul>
        </div>
    </div>
</div>
<!--省市区-->
<div id="sjld">
	<div class="m_zlxg" id="shenfen">
		<p title="">请选择</p>
		<div class="m_zlxg2">
			<ul></ul>
		</div>
	</div>
	<div class="m_zlxg" id="chengshi">
		<p title="">请选择</p>
		<div class="m_zlxg2">
			<ul></ul>
		</div>
	</div>
	<div class="m_zlxg" id="quyu">
		<p title="">请选择</p>
		<div class="m_zlxg2">
			<ul></ul>
		</div>
	</div>
	<input id="sfdq_num" type="hidden" value="" />
	<input id="csdq_num" type="hidden" value="" />
	<input id="sfdq_tj" type="hidden" value="" />
	<input id="csdq_tj" type="hidden" value="" />
	<input id="qydq_tj" type="hidden" value="" />
</div>
<!--街道-->
<div id="street">
    <div class="street_content">
        <div class="street_bar">
            <ul class="street_bar_ul"></ul>
        </div>
    </div>
</div>
<!--协议-->
<div id="containter">
    <h1 class="title">凡商优店商城商家入驻协议</h1>
    <div class="mar_b30">
    <p class="not_indent_content">甲方：合肥瘦小猴网络科技合伙企业（有限合伙）（以下简称甲方）</p>
    <p class="not_indent_content">乙方：凡商优店电商平台入驻商家（以下简称乙方）</p>
    </div>
    <p class="content">本协议由缔约双方在自愿、平等、公平及诚实信用原则基础上，根据《中华人民共和国合同法》、《消费者权益保护法》等相关法律、法规的规定，经友好协商缔结。</p>
    <p class="content">本协议由协议正文、附件及依据本协议公示于甲方平台的各项规则所组成，协议附件及规则与本协议具有同等法律效力，如规则与本协议约定不一致，以公布生效日期或签署日期在后的文件为准执行。</p>
    <div class="mar_tb30">
        <p class="not_indent_content">第一条<span class="mar_lf30">合作方式</span></p>
    </div>
    <p class="not_indent_content">1.1&nbsp;商城合作形式</p>
    <p class="content">本协议所称合作，指由甲方所有并运营的“凡商优店网上商城”提供电子商务平台并代收贷款、由乙方提供商品，并在甲方平台展示和销售，同时由乙方自行向终端消费者提供商品、物流配送、售后服务等，双方联合经营的经营模式。</p>
    <p class="content">1.1.1&nbsp;甲方平台：指由甲方提供技术支持和服务的电子商务网站，由甲方提供技术服务或移动端交易平台（包括但不限于甲方APP、甲方PC平台等）。随着甲方服务范围或服务项目的变更，甲方可能在平台规则或公告中对甲方平台的范围或域名调整予以声明。</p>
    <p class="content">1.1.2&nbsp;用户：指使用甲方平台服务的自然人、法人或其他组织。</p>
    <p class="content">1.1.3&nbsp;商品：指在甲方平台上销售或展示的商品和服务。</p>
    <p class="content">1.1.4&nbsp;商家：指在甲方平台上出售或提供商品的用户（商标持有人资质等公司性质企业）。</p>
    <p class="content">1.1.5&nbsp;消费者：指在甲方平台上购买商品的用户。</p>
    <p class="content">1.1.6&nbsp;商家后台：商家在甲方平台经甲方审核以及完成必要的入驻流程后，由甲方给予商家移动APP端的商家管理后台和包括独立的账号，商家在该后台完成产品编辑、上传、设置运费、处理订单、申请提现等相关事宜。</p>
    <p class="content">1.1.7&nbsp;确认交易：指消费者在凡商优店平台向乙方购买商品，通过凡商优店平台提供之后台系统付款并确认收货的交易。</p>
    <p class="content">1.1.8&nbsp;交易服务费：指甲方根据确认交易的商品价款向乙方收取的服务费用，此费用不包括网上支付涉及的银行交易手续费，也不涉及商品配送所产生的物流费用。</p>
    <p class="content">1.1.9&nbsp;平台规则：指在甲方平台上已经发布或将来可能发布的各种规范性文件，包括但不限于《凡商优店商城商家入驻协议》及其他细则、规范、政策、通知等规范性文件。</p>
    <p class="content">1.1.10&nbsp;平台技术服务：以下或称“平台服务”指甲方依托甲方平台向乙方提供的服务。主要包括店铺管理、商品发布、浏览、信息交流、商品交易、推广营销以及其他甲方各个平台上所提供的无偿或有偿的技术服务。</p>
    <div class="mar_tb30">
        <p class="not_indent_content">第二条&nbsp;商家资格要求以及证明文件</p>
    </div>
    <p class="not_indentonly_content">1、乙方申请及开展店铺经营活动，须持续的同时满足以下基本条件：</p>
    <p class="not_indentonly_content">（1） 乙方已依照中华人民共和国法律注册并领取合法有效的营业执照及取得其他经营许可，身份信息应为商家自身情况的客观表现；</p>
    <p class="not_indentonly_content">（2） 乙方所经营的商品来源合法，资质齐全；</p>
    <p class="not_indentonly_content">（3） 乙方提交的任何信息均真实、合法、有效，所使用的图片、文字等不侵犯任何第三方合法权益；</p>
    <p class="not_indentonly_content">（4） 乙方签署本协议并同意甲方平台规则内容；</p>
    <p class="not_indentonly_content">（5） 甲方依据国家法律法规、政策或其他规范性文件规定及经营需要可能设定的其他条件。</p>

    <p class="not_indentonly_content">2、证明文件明细</p>
    <p class="not_indentonly_content">（1） 乙方应依据平台规则及相应的入驻流程要求完成甲方平台的入驻及店铺开设，在线签订甲方平台所公示的需要卖家签订的相应协议。乙方应向甲方提交各项为经营店铺所必须的资质、证照、证明或其他相关文件(以下统称"证明文件")，包括但不限于三证合一 (营业执照、税务登记证、组织机构代码)、开户行证明、 授权委托书、商标注册证、质检报告、法定代表人身份证正、反面复印件等，乙方应向甲方提交与原件核对一致且加盖乙方公章的纸质复印件(根据实际业务提供相应资质)。</p>
    <p class="not_indentonly_content">（2） 乙方保证上述证明文件发生任何变更或更新时立即通知甲方，并于变更或更新之日起十五个工作日内，提交更新后的文件并依据甲方平台实时公布的流程进行相应认证。</p>
    <p class="not_indentonly_content">（3） 乙方提交虚假、过期文件、或未如期通知并提交更新文件等情形的，由乙方独立承担全部法律责任。若由此导致乙方的店铺信息不符合甲方平台所公示的店铺认证条件的，甲方有权要求乙方补充提供相关资料，或者拒绝乙方申请、调整卖家权限、直至终止本协议。如乙方造成甲方及其他任何第三方损失的，乙方还应承担全部责任并足额赔偿。</p>
    <div class="mar_tb30">
        <p class="not_indent_content">第四条<span class="mar_lf30">双方权利义务</span></p>
    </div>
    <p class="not_indentonly_content">1、甲方在现有技术实现基础上努力维护甲方平台的正常稳定运行，并努力提升和改进技术，对平台功能及服务进行更新、升级，以不断提升平台性能和交易效率。如发现卖家有损系统安全、稳定操作的，甲方有权立即停止为乙方继续提供平台服务，并立即删除所有有害信息、数据等;乙方应对此导致的一切不利后果承担全部法律责任，包括但不限于赔偿甲方及其关联公司/机构、消费者或其他任何第三方的损失。</p>
    <p class="not_indentonly_content">2、甲方有权根据甲方平台的发展规划自主选择是否对通过资质审核的乙方开设店铺，提供平台服务。而未承诺必然对所有申请认证的卖家承诺开通权限；同时有权依据独立判断，不受时间限制的审批卖家的认证申请。</p>
    <p class="not_indentonly_content">3、甲方有权单方根据国家相关法律法规、政策及甲方平台运营情况，对公示于甲方平台规则进行变更，变更后的规则甲方将以公告形式告知乙方，任何变更一经公告即构成本协议的组成部分。</p>
    <p class="not_indentonly_content">4、甲方有权对乙方的申请信息、上传的相关数据信息、在甲方平台发布的其他信息、交易行为进行监督检查，对发现的涉嫌违反法律法规、违反平台规则的信息及其相关内容，乙方知晓并同意甲方有权不经通知直接删除，对发现的其他问题或疑问，甲方发出询问及要求改正的通知，乙方应在接到通知后立即做出说明并提供相关证明材料或改正。</p>
    <p class="not_indentonly_content">5、甲方有权将国家生效法律文书或行政文书确定的乙方违法违规事件，或已确认的乙方违反本协议相关约定的事项，在甲方平台上予以公示；乙方违规或者有严重违约、违规情形的，甲方有权对其采取限制权限、依据平台规则进行违规处理、扣除保证金直至终止本协议等措施，上述措施不足以补偿甲方及其关联公司/机构、消费者或其他任何第三方损失的，乙方还应足额赔偿。</p>
    <p class="not_indentonly_content">6、如乙方的运营情况不能满足甲方平台公布的要求(包括但不限于甲方平台规则等)，经限期整改调整后，仍无法满足的，视为乙方违约，甲方有权解除本协议，停止向乙方提供服务。</p>
    <p class="not_indentonly_content">7、乙方有义务对其在甲方平台销售的每款商品按照国家标准，行业标准及甲方发布的各品类商品要求进行质量控制(包括且不仅限于商品法律法规符合性，商品安全性，商品功能材质与描述符合性，商品标识标示，商品外观，商品包装等)，并依照国家法律法规提供售后三包服务。甲方有权根据市场反馈自行或委托第三方质检机构进行不定期商品抽检(检测项目包括且不仅限于乙方销售商品的性能，质量，材料成分，是否符合国家法律法规要求等各方面;抽检方式包括但不限于匿名购买、对于卖家订单信息及页面信息进行检查等)，或要求乙方对甲方指定商品提供进货凭证，出厂检验报告或者第三方质检机构出具的检测报告等相关商品及批次的质量合格证明文件。如果乙方所销售商品抽检不合格或无法向甲方提供相关商品及批次质量合格的证明文件，甲方有权根据本协议及甲方平台所公示的平台规则，规规范及标准，并且依据问题的严重程度对乙方提出相应的限期整改要求、进行违规处理以及追究相应的违约责任。</p>
    <p class="not_indentonly_content">8、甲方有权要求乙方提供与乙方商品、售后服务等相关的信息，以便于消费者直接向甲方平台客服中心进行咨询时予以回复，对于甲方无法回答或属卖家掌握的情况，甲方有权要求乙方在指定的时限内予以回复或给出相应方案，如乙方未及时予以解决的客户咨询及投诉，甲方有权对乙方采取相应处理措施。</p>
    <p class="not_indentonly_content">9、乙方同意甲方根据乙方营业执照所载明的经营范围及乙方申请的经营类目， 核实及调整乙方在甲方平台经营的具体商品的种类、数量和类目范围。</p>
    <p class="not_indentonly_content">10、如因乙方商品、发布的信息或提供的售后服务问题而引发客户对甲方及其关联公司/机构的诉讼，甲方及其关联公司/机构有权披露乙方为实际商品提供商，乙方应承担因诉讼而产生的全部法律责任，如因此而给甲方及其关联公司/机构造成损失的，甲方有权要求乙方赔偿甲方及其关联公司/机构的全部损失。</p>
    <p class="not_indentonly_content">11、乙方同意并授权甲方代收买家货款，同时同意并授权甲方以甲方名义指令第三方支付机构将买家支付货款转账至甲方指定支付账户中，依据甲乙双方共同确认的结算流程，自买家确认收货后，将货款结算至乙方账户。</p>
    <p class="not_indentonly_content">12、经双方约定并确认，乙方违反本协议时，应立即向甲方承担法律责任(包括但不限于支付违约金、赔偿金等)，同时，甲方有权暂缓支付未结算款项。</p>
    <p class="not_indentonly_content">13、乙方有义务按照买家实际支付的金额为买家开具发票，相关税收应按国家相关规定由商户自行承担。</p>
    <div class="mar_tb30">
        <p class="not_indent_content">第五条<span class="mar_lf30">保密义务</span></p>
    </div>
    <p class="not_indentonly_content">1、一方对于本协议的签订、内容及在履行本协议期间所获知的其他方的商业秘密负有保密义务。非经相对方书面同意，不得向相对方以外的任何第三方(关联公司除外)泄露、给予或转让本协议以及其他相关信息。</p>
    <p class="not_indentonly_content">2、如相对方提出要求，任何一方均应将载有相对方单独所有的保密信息的任何文件、资料或软件等，在本协议终止后按对方要求归还对方，或予以销毁，或进行其他处置，并且不得继续使用这些保密信息。</p>
    <p class="not_indentonly_content">3、在本协议终止之后，各方在本条款项下的义务并不随之终止，各方仍需遵守本协议之保密条款，履行其所承诺的保密义务，直到其他方同意其解除此项义务，或事实上不会因违反本协议的保密条款而给其他方造成任何形式的损害时为止。</p>
    <p class="not_indentonly_content">4、任何一方均应告知并督促其因履行本协议之目的而必须获知本协议内容及因合作而获知对方商业秘密的雇员、代理人、顾问等遵守保密条款，并对其雇员、代理人、顾问等的行为承担责任。</p>
    <div class="mar_tb30">
        <p class="not_indent_content">第六条<span class="mar_lf30">违约责任</span></p>
    </div>
    <p class="not_indentonly_content">1、乙方的经营导致纠纷或政府部门查处的，乙方应按甲方的要求提供相应的证明材料，必要时，应自行或由甲方协助或授权甲方或其关联公司/机构处理解决。由此导致的甲方或其关联公司/机构损失(损失包括但不限于诉讼费、律师费、赔偿、补偿、行政机关处罚、差旅费等)，乙方应足额赔偿。</p>
    <p class="not_indentonly_content">2、乙方有销售假冒伪劣商品等侵犯知识产权行为、涉嫌非法信用卡套现等违法活动，并有可能涉嫌违法甚至刑事犯罪的，甲方有权向相应机关( 包括但不限于行政监管机关、公安机关、司法机关以及其他有权机关)提供乙方一切资料及信息，在甲方平台披露乙方涉嫌违法及犯罪行为及情况，并配合相应机关的调查取证及侦查工作，乙方应承担由此产生的一切责任并赔偿甲方平台由此遭受的所有损失。</p>
    <p class="not_indentonly_content">3、乙方违反乙方于本协议项下的陈述、承诺、保证和义务，或违反甲方平台规则以及乙方所在线签订的甲方平台所公示的在线协议，均构成对于本协议的违约。如乙方发生违约行为，甲方可以本协议约定的通知方式要求乙方在指定的时限内停止违约行为，要求其消除影响并依据平台规则对于乙方违约行为进行违规处理。乙方发生违约情形时，甲方除有权按照本协议约定要求乙方承担违约责任外，还有权依据本协议其他约定限制卖家权限、扣除保证金(如有)、 解除本协议等措施。</p>
    <div class="mar_tb30">
        <p class="not_indent_content">第七条<span class="mar_lf30">通知及送达</span></p>
    </div>
    <p class="not_indentonly_content">1、针对乙方的服务费用收费标准变更、规则变更、保证金标准、通知等将以甲方平台公告形式告知乙方，公告一经发布，即视为送达，卖家应实时关注平台公告内容。</p>
    <p class="not_indentonly_content">2、甲方针对某特定卖家的通知等，将以短信形式发送，则以甲方发出之时即视为已送达卖家。</p>
    <div class="mar_tb30">
        <p class="not_indent_content">第八条<span class="mar_lf30">争议解决</span></p>
    </div>
    <p class="not_indentonly_content">1、履行本协议过程中产生的任何争议，协议方应协商解决，协商不成的，任一方有权将争议提交本协议签订地有管辖权的法院诉讼解决。</p>
    <p class="not_indentonly_content">2、本协议的签订、解释、变更、履行及争议的解决等均适用中华人民共和国大陆地区现行有效的法律。</p>
    <div class="mar_tb30">
        <p class="not_indent_content">第九条<span class="mar_lf30">其他约定</span></p>
    </div>
    <p class="not_indentonly_content">1、本协议的任何一方未能及时行使本协议项下的权利不应被视为放弃该权利，也不影响该方在将来行使该权利。</p>
    <p class="not_indentonly_content">2、如果本协议中的任何条款无论因何种原因完全或部分无效或不具有执行力，或违反任何适用的法律，则该条款被视为删除，但本协议的其余条款仍应具有法律约束力。</p>
    <p class="not_indentonly_content">3、本协议是缔约协议方之间关于本协议中提及合作事项的完整的、唯-的协议，本协议取代了任何先前的关于该合作事项的协议和沟通(包括数据电文形式、书面形式和口头形式)。</p>
    <p class="not_indentonly_content">4、本协议文本以中华人民共和国通用简体汉字版本为准。</p>
    <p class="not_indentonly_content">5、本协议自双方盖章之日起生效，壹式贰份，甲乙双方各执壹份，具有同等法律效力。</p>
    <div class="mar_tb30">
        <p class="not_indent_content">第十条<span class="mar_lf30">协议有效期</span></p>
    </div>
    <p class="not_indentonly_content">本协议长期有效。</p>
    <div class="mar_tb30">
        <p class="not_indent_content">第十一条<span class="mar_lf30">附则</span></p>
    </div>
    <p class="not_indentonly_content">1、商家入驻凡商优店电商平台除遵守上述合同条款约定外，还需遵守凡商优店平台商家管理规则(详见附件)。商家管理规则为本合同组成的一部分，具有同样法律效力。</p>
    <p class="not_indentonly_content"><a href="#">《凡商优店商城商家入驻须知》</a>、<a href="#">《凡商优店电商平台争议处理规定》</a>、<a href="#">《凡商优店电商产品上架标准细则》</a>、<a href="#">《凡商优店电商平台商品详情发布规范》</a>、<a href="#">《凡商优店电商平台商家发货须知》</a>、<a href="#">《凡商优店电商平台退换货须知》</a>、<a href="#">《凡商优店电商平台退款须知》</a>、<a href="#">《凡商优店电商平台商家违规处理办法》</a>。</p>
</div>
<script type="text/javascript" src="/public/static/js/store/citySelect.js"></script>
<script type="text/javascript" src="/public/static/js/store/store_apply.js"></script>
<script type="text/javascript" src="/public/static/js/store/public_function.js"></script>
<script type="text/javascript">
    // 跳转
    $('.submit_btn').on('click',function(){
        // window.location.href="<?php echo U('Admin/store/storeManage'); ?>";
    })
</script>
</html>