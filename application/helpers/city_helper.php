<?php


function getCityNameHelper($city)
{
    $parma_helper_city = [
        "xicheng" => "西城",
        "dongcheng" => "东城",
        "chongwen" => "崇文",
        "xuanwu" => "宣武",
        "chaoyang" => "潮阳",
        "haidian" => "海淀",
        "fengtai" => "丰台",
        "shijingshan" => "石景山",
        "mentougou" => "门头沟",
        "fangshan" => "房山",
        "tongzhou" => "通州",
        "shunyi" => "顺义",
        "daxing" => "大兴",
        "changping" => "昌平",
        "pinggu" => "平谷",
        "huairou" => "怀柔",
        "miyun" => "密云",
        "yanqing" => "延庆",
        "qingyang" => "青羊",
        "hedong" => "河东",
        "hexi" => "河西",
        "nankai" => "南开",
        "hebei" => "河北",
        "hongqiao" => "红桥",
        "tanggu" => "塘沽",
        "hangu" => "汉沽",
        "dagang" => "大港",
        "dongli" => "东丽",
        "xiqing" => "西青",
        "beichen" => "北辰",
        "jinnan" => "津南",
        "wuqing" => "武清",
        "baodi" => "宝坻",
        "jinghai" => "静海",
        "ninghe" => "宁河",
        "jixian" => "蓟县",
        "kaifaqu" => "开发区",
        "shijiazhuang" => "石家庄",
        "qinhuangdao" => "秦皇岛",
        "langfang" => "廊坊",
        "baoding" => "保定",
        "handan" => "邯郸",
        "tangshan" => "唐山",
        "xingtai" => "邢台",
        "hengshui" => "衡水",
        "zhangjiakou" => "张家口",
        "chengde" => "承德",
        "cangzhou" => "沧州",
        "taiyuan" => "太原",
        "datong" => "大同",
        "changzhi" => "长治",
        "jinzhong" => "晋中",
        "yangquan" => "阳泉",
        "shuozhou" => "朔州",
        "yuncheng" => "运城",
        "linfen" => "临汾",
        "huhehaote" => "呼和浩特",
        "chifeng" => "赤峰",
        "tongliao" => "通辽",
        "xilinguole" => "锡林郭勒",
        "xingan" => "兴安",
        "dalian" => "大连",
        "shenyang" => "沈阳",
        "anshan" => "鞍山",
        "fushun" => "抚顺",
        "yingkou" => "营口",
        "jinzhou" => "锦州",
        "dandong" => "丹东",
        "liaoyang" => "辽阳",
        "fuxin" => "阜新",
        "tieling" => "铁岭",
        "panjin" => "盘锦",
        "benxi" => "本溪",
        "huludao" => "葫芦岛",
        "changchun" => "长春",
        "jilin" => "吉林",
        "siping" => "四平",
        "liaoyuan" => "辽源",
        "tonghua" => "通化",
        "yanji" => "延吉",
        "baicheng" => "白城",
        "songyuan" => "松原",
        "linjiang" => "临江",
        "huichun" => "珲春",
        "haerbin" => "哈尔滨",
        "qiqihaer" => "齐齐哈尔",
        "daqing" => "大庆",
        "mudanjiang" => "牡丹江",
        "hegang" => "鹤岗",
        "jiamusi" => "佳木斯",
        "suihua" => "绥化",
        "pudong" => "浦东",
        "yangpu" => "杨浦",
        "xuhui" => "徐汇",
        "jingan" => "静安",
        "luwan" => "卢湾",
        "huangpu" => "黄浦",
        "putuo" => "普陀",
        "zhabei" => "闸北",
        "hongkou" => "虹口",
        "changning" => "长宁",
        "baoshan" => "宝山",
        "minxing" => "闵行",
        "jiading" => "嘉定",
        "jinshan" => "金山",
        "songjiang" => "松江",
        "qingpu" => "青浦",
        "chongming" => "崇明",
        "fengxian" => "奉贤",
        "nanhui" => "南汇",
        "nanjing" => "南京",
        "suzhou" => "苏州",
        "wuxi" => "无锡",
        "changzhou" => "常州",
        "yangzhou" => "扬州",
        "xuzhou" => "徐州",
        "nantong" => "南通",
        "zhenjiang" => "镇江",
        "taizhou" => "台州",
        "huaian" => "淮安",
        "lianyungang" => "连云港",
        "suqian" => "宿迁",
        "yancheng" => "盐城",
        "huaiyin" => "淮阴",
        "muyang" => "沐阳",
        "zhangjiagang" => "张家港",
        "hangzhou" => "杭州",
        "jinhua" => "金华",
        "ningbo" => "宁波",
        "wenzhou" => "温州",
        "jiaxing" => "嘉兴",
        "shaoxing" => "绍兴",
        "lishui" => "丽水",
        "huzhou" => "湖州",
        "zhoushan" => "舟山",
        "quzhou" => "衢州",
        "hefei" => "合肥",
        "maanshan" => "马鞍山",
        "bangbu" => "蚌埠",
        "huangshan" => "黄山",
        "wuhu" => "芜湖",
        "huainan" => "淮南",
        "tongling" => "铜陵",
        "fuyang" => "阜阳",
        "xuancheng" => "宣城",
        "anqing" => "安庆",
        "fuzhou" => "福州",
        "xiamen" => "厦门",
        "quanzhou" => "泉州",
        "zhangzhou" => "漳州",
        "nanping" => "南平",
        "longyan" => "龙岩",
        "putian" => "莆田",
        "sanming" => "三明",
        "ningde" => "宁德",
        "nanchang" => "南昌",
        "jingdezhen" => "景德镇",
        "shangrao" => "上饶",
        "pingxiang" => "萍乡",
        "jiujiang" => "九江",
        "jian" => "吉安",
        "yichun" => "宜春",
        "yingtan" => "鹰潭",
        "xinyu" => "新余",
        "ganzhou" => "赣州",
        "qingdao" => "青岛",
        "jinan" => "济南",
        "zibo" => "淄博",
        "yantai" => "烟台",
        "taian" => "泰安",
        "linyi" => "临沂",
        "rizhao" => "日照",
        "dezhou" => "德州",
        "weihai" => "威海",
        "dongying" => "东营",
        "heze" => "荷泽",
        "jining" => "济宁",
        "weifang" => "潍坊",
        "zaozhuang" => "枣庄",
        "liaocheng" => "聊城",
        "zhengzhou" => "郑州",
        "luoyang" => "洛阳",
        "kaifeng" => "开封",
        "pingdingshan" => "平顶山",
        "puyang" => "濮阳",
        "anyang" => "安阳",
        "xuchang" => "许昌",
        "nanyang" => "南阳",
        "xinyang" => "信阳",
        "zhoukou" => "周口",
        "xinxiang" => "新乡",
        "jiaozuo" => "焦作",
        "sanmenxia" => "三门峡",
        "shangqiu" => "商丘",
        "wuhan" => "武汉",
        "xiangfan" => "襄樊",
        "xiaogan" => "孝感",
        "shiyan" => "十堰",
        "jingzhou" => "荆州",
        "huangshi" => "黄石",
        "yichang" => "宜昌",
        "huanggang" => "黄冈",
        "enshi" => "恩施",
        "ezhou" => "鄂州",
        "jianghan" => "江汉",
        "suizao" => "随枣",
        "jingsha" => "荆沙",
        "xianning" => "咸宁",
        "changsha" => "长沙",
        "xiangtan" => "湘潭",
        "yueyang" => "岳阳",
        "zhuzhou" => "株洲",
        "huaihua" => "怀化",
        "yongzhou" => "永州",
        "yiyang" => "益阳",
        "zhangjiajie" => "张家界",
        "changde" => "常德",
        "hengyang" => "衡阳",
        "xiangxi" => "湘西",
        "shaoyang" => "邵阳",
        "loudi" => "娄底",
        "chenzhou" => "郴州",
        "guangzhou" => "广州",
        "shenzhen" => "深圳",
        "dongwan" => "东莞",
        "foshan" => "佛山",
        "zhuhai" => "珠海",
        "shantou" => "汕头",
        "shaoguan" => "韶关",
        "jiangmen" => "江门",
        "meizhou" => "梅州",
        "jieyang" => "揭阳",
        "zhongshan" => "中山",
        "heyuan" => "河源",
        "huizhou" => "惠州",
        "maoming" => "茂名",
        "zhanjiang" => "湛江",
        "yangjiang" => "阳江",
        "chaozhou" => "潮州",
        "yunfu" => "云浮",
        "shanwei" => "汕尾",
        "zhaoqing" => "肇庆",
        "shunde" => "顺德",
        "qingyuan" => "清远",
        "nanning" => "南宁",
        "guilin" => "桂林",
        "liuzhou" => "柳州",
        "wuzhou" => "梧州",
        "laibin" => "来宾",
        "guigang" => "贵港",
        "yulin" => "榆林",
        "hezhou" => "贺州",
        "haikou" => "海口",
        "sanya" => "三亚",
        "yuzhong" => "渝中",
        "dadukou" => "大渡口",
        "jiangbei" => "江北",
        "shapingba" => "沙坪坝",
        "jiulongpo" => "九龙坡",
        "nanan" => "南岸",
        "beibei" => "北碚",
        "wansheng" => "万盛",
        "shuangqiao" => "双桥",
        "yubei" => "渝北",
        "banan" => "巴南",
        "wanzhou" => "万州",
        "fuling" => "涪陵",
        "qianjiang" => "黔江",
        "changshou" => "长寿",
        "chengdu" => "成都",
        "dazhou" => "达州",
        "nanchong" => "南充",
        "leshan" => "乐山",
        "mianyang" => "绵阳",
        "deyang" => "德阳",
        "najiang" => "内江",
        "suining" => "遂宁",
        "yibin" => "宜宾",
        "bazhong" => "巴中",
        "zigong" => "自贡",
        "kangding" => "康定",
        "panzhihua" => "攀枝花",
        "guiyang" => "贵阳",
        "zunyi" => "遵义",
        "anshun" => "安顺",
        "qianxinan" => "黔西南",
        "douyun" => "都匀",
        "kunming" => "昆明",
        "lijiang" => "丽江",
        "zhaotong" => "昭通",
        "yuxi" => "玉溪",
        "lincang" => "临沧",
        "wenshan" => "文山",
        "honghe" => "红河",
        "chuxiong" => "楚雄",
        "dali" => "大理",
        "lasa" => "拉萨",
        "linzhi" => "林芝",
        "rikaze" => "日喀则",
        "changdou" => "昌都",
        "xian" => "西安",
        "xianyang" => "咸阳",
        "yanan" => "延安",
        "hanzhong" => "汉中",
        "shangnan" => "商南",
        "lueyang" => "略阳",
        "yijun" => "宜君",
        "linyou" => "麟游",
        "baihe" => "白河",
        "lanzhou" => "兰州",
        "jinchang" => "金昌",
        "tianshui" => "天水",
        "wuwei" => "武威",
        "zhangye" => "张掖",
        "pingliang" => "平凉",
        "jiuquan" => "酒泉",
        "huangnan" => "黄南",
        "hainan" => "海南",
        "xining" => "西宁",
        "haidong" => "海东",
        "haixi" => "海西",
        "haibei" => "海北",
        "guoluo" => "果洛",
        "yushu" => "玉树",
        "yinchuan" => "银川",
        "wuzhong" => "吴忠",
        "wulumuqi" => "乌鲁木齐",
        "hami" => "哈密",
        "kashi" => "喀什",
        "bayinguoleng" => "巴音郭楞",
        "changji" => "昌吉",
        "yili" => "伊犁",
        "aletai" => "阿勒泰",
        "kelamayi" => "克拉玛依",
        "boertala" => "博尔塔拉",
        "zhongxiqu" => "中西区",
        "wanziqu" => "湾仔区",
        "dongqu" => "东区",
        "nanqu" => "南区",
        "jiulong-youjianwangqu" => "九龙-油尖旺区",
        "jiulong-shenshuibuqu" => "九龙-深水埗区",
        "jiulong-jiulongchengqu" => "九龙-九龙城区",
        "jiulong-huangdaxianqu" => "九龙-黄大仙区",
        "jiulong-guantangqu" => "九龙-观塘区",
        "xinjie-beiqu" => "新界-北区",
        "xinjie-dapuqu" => "新界-大埔区",
        "xinjie-shatianqu" => "新界-沙田区",
        "xinjie-xigongqu" => "新界-西贡区",
        "xinjie-quanwanqu" => "新界-荃湾区",
        "xinjie-tunmenqu" => "新界-屯门区",
        "xinjie-yuanlangqu" => "新界-元朗区",
        "xinjie-kuiqingqu" => "新界-葵青区",
        "xinjie-lidaoqu" => "新界-离岛区",
        "huadimatangqu" => "花地玛堂区",
        "shenganduonitangqu" => "圣安多尼堂区",
        "datangqu" => "大堂区",
        "wangdetangqu" => "望德堂区",
        "fengshuntangqu" => "风顺堂区",
        "jiamotangqu" => "嘉模堂区",
        "shengfangjigetangqu" => "圣方济各堂区",
        "ludangcheng" => "路氹城"
    ];
    if (isset($parma_helper_city[$city]) && !empty($parma_helper_city[$city])) {
        return $parma_helper_city[strtolower($city)];
    }
    return "";
}
