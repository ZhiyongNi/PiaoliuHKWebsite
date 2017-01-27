<?php
namespace configs\globalconstant;

class PiaoliuHK_Configs_GlobalConstant_StationAddress
{

    const Country = "中国";

    const Province = "广东";

    const City = "深圳市";

    const District = "龙岗区";

    const Streets = "横岗街道";

    const Apartment = "振业城综合大楼176号商铺漂流瓶";

    const ZipCode = "518173";

    const Mobile = "15818786460";

    const Tele = "12345678";
}

class PiaoliuHK_Configs_GlobalConstant_TransitBillMethod
{

    const ExpressPick = 0;

    const ExpressChinese = "A.学校及居住区定点派送 ";

    const ExpressShortChinese = "送货上门";

    const HousePick = 1;

    const HousePickChinese = "B.香港仓库自行取货";

    const HousePickShortChinese = "仓库自取";

    const SelfPick = 2;

    const SelfPickChinese = "C.送往指定地点门 ";

    const SelfPickShortChinese = "服务点待提";
}

class PiaoliuHK_Configs_GlobalConstant_SelfPickAddress
{

    const ChineseUniversityofHongKong = 1;

    const ChineseUniversityofHongKongAddress = "香港中文大学（大学地铁站A出口）";

    const CityUniversityofHongKong = 2;

    const CityUniversityofHongKongAddress = "香港城市大学（香港城市法学图书馆门口）";

    const HongKongBaptistUniversity = 3;

    const HongKongBaptistUniversityAddress = "香港浸会大学（九龙塘地铁站A2出口）";

    const HongKongPolytechnicUniversity = 4;

    const HongKongPolytechnicUniversityAddress = "香港理工大学（红磡地铁站A1出口）";
}

class PiaoliuHK_Configs_GlobalConstant_PackageStatus
{

    const Signed = 0;

    const SignedChinese = "已签收";

    const inTransit = 1;

    const inTransitChinese = "在途";

    const Checkout = 2;

    const CheckoutChinese = "待出库";

    const Waiting = 3;

    const WaitingChinese = "在库，待操作";

    const Unmatched = 4;

    const UnmatchedChinese = "未匹配";

    const Lost = 5;

    const LostChinese = "丢失物品";

    const Reservation = 6;

    const ReservationChinese = "预约，尚未入库";
}

class PiaoliuHK_Configs_GlobalConstant_TransitBillStatus
{

    const Signed = 0;

    const SignedChinese = "已签收";

    const inShip = 1;

    const inShipChinese = "在途";

    const Checkout = 2;

    const CheckoutChinese = "待出库";
}

class PiaoliuHK_Configs_GlobalConstant_PasswordAuthResult
{

    const Right = 1;

    const Wrong = 2;

    const None = 0;
}

class PiaoliuHK_Configs_GlobalConstant_UserType
{

    const Admin = 0;

    const Customer = 1;
}

class PiaoliuHK_Configs_GlobalConstant_CustomerAccountStatus
{

    const Initialization = 0;

    const Unverified = 1;

    const Verified = 2;
}

class PiaoliuHK_Configs_GlobalConstant_SearchObjectType
{

    const Package = 0;

    const TransitBill = 1;
}

class PiaoliuHK_Configs_GlobalConstant_SignatureVerifyResult
{

    const Right = 1;

    const Wrong = 2;

    const None = 0;
}

class PiaoliuHK_Configs_GlobalConstant_PackageChannel
{

    const Unknown = - 1;

    const UnknownChinese = "未知";

    const TaoBaoWEB = 0;

    const TaoBaoWEBChinese = "淘宝/天猫";

    const JingDongWEB = 1;

    const JingDongWEBChinese = "京东";

    const DangdangWEB = 2;

    const DangdangWEBChinese = "当当";

    const AmazonWEB = 3;

    const AmazonWEBChinese = "亚马逊";

    const VIPWEB = 4;

    const VIPWEBChinese = "唯品会";
}

class PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany
{

    const Unknown = - 1;

    const UnknownCode = "UnknownCode";

    const UnknownChinese = "未知";

    const ShunfengExpress = 0;

    const ShunfengExpressCode = "shunfeng";

    const ShunfengExpressChinese = "顺丰快递";

    const YuantongExpress = 1;

    const YuantongExpressCode = "yuantong";

    const YuantongExpressChinese = "圆通快递";

    const ZhongtongExpress = 2;

    const ZhongtongExpressCode = - "zhongtong";

    const ZhongtongExpressChinese = "中通快递";

    const YundaExpress = 3;

    const YundaExpressCode = "yunda";

    const YundaExpressChinese = "韵达快递";

    const ShentongExpress = 4;

    const ShentongExpressCode = "shentong";

    const ShentongExpressChinese = "申通快递";

    const EMSExpress = 5;

    const EMSExpressCode = "ems";

    const EMSExpressChinese = "EMS快递";

    const HuitongExpress = 6;

    const HuitongExpressCode = "huitong";

    const HuitongExpressChinese = "汇通快递";

    const TiantianExpress = 7;

    const TiantianExpressCode = "tiantian";

    const TiantianExpressChinese = "天天快递";

    const YousuExpress = 8;

    const YousuExpressCode = "yousu";

    const YousuExpressChinese = "优速快递";

    const JingdongExpress = 9;

    const JingdongExpressCode = "jingdong";

    const JingdongExpressChinese = "京东快递";

    const GuotongExpress = 10;

    const GuotongExpressCode = "guotong";

    const GuotongExpressChinese = "国通快递";

    const LongbangExpress = 11;

    const LongbangExpressCode = "longbang";

    const LongbangExpressChinese = "龙邦快递";

    const SuerExpress = 12;

    const SuerExpressCode = "suer";

    const SuerExpressChinese = "速尔快递";

    const HuiqiangExpress = 13;

    const HuiqiangExpressCode = "huiqiang";

    const HuiqiangExpressChinese = "汇强快递";

    const ZJSExpress = 14;

    const ZJSExpressCode = "zjs";

    const ZJSExpressChinese = "宅急送快递";

    const QuanfengExpress = 15;

    const QuanfengExpressCode = "quanfeng";

    const QuanfengExpressChinese = "全峰快递";

    const DangdangExpress = 100;

    const DangdangExpressCode = "dangdang";

    const DangdangExpressChinese = "当当官方快递";

    const AmazonExpress = 101;

    const AmazonExpressCode = "amazon";

    const AmazonExpressChinese = "亚马逊官方快递";
}

class PiaoliuHK_Configs_GlobalConstant_PackageTrackStatus
{

    const Error = 0;

    const NoRecord = 1;

    const OntheWay = 2;

    const Delivery = 3;

    const Signed = 4;

    const Reject = 5;

    const Difficult = 6;

    const Returned = 7;
}

class PiaoliuHK_Configs_GlobalConstant_TransitBillTrackStatus
{

    const Error = 0;

    const NoRecord = 1;

    const OntheWay = 2;

    const Delivery = 3;

    const Signed = 4;

    const Reject = 5;

    const Difficult = 6;

    const Returned = 7;
}

class PiaoliuHK_Configs_GlobalConstant_MaxSnapNum
{

    const PiaoliuHK_Configs_GlobalConstant_PackageInfo = 5;

    const PiaoliuHK_Configs_GlobalConstant_PackageInfoRow = 4;

    const PiaoliuHK_Configs_GlobalConstant_TransitBillInfo = 3;
}
?>