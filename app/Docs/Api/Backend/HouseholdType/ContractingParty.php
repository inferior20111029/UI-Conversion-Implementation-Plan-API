<?php

namespace App\Docs\Api\Backend\HouseholdType;

class ContractingParty
{
    /**
     * @OA\Get(
     *     path="/household-type/{spaceid}",
     *     tags={"Household-Type 專有空間-戶別列表-立約人資料"},
     *     summary="獲取產權列表資訊",
     *     description="獲取產權列表資訊",
     *     security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *     @OA\Parameter(
     *         name="spaceid",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="取得成功",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="取得成功"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="is_edit", type="integer", example=1, description="是否可編輯"),
     *                 @OA\Property(property="sign_date", type="string", example="1120526", description="簽約日期"),
     *                 @OA\Property(property="transfer_date", type="string", example="1120526", description="轉移日期"),
     *                 @OA\Property(property="build_date", type="string", example="1120526", description="建造日期"),
     *                 @OA\Property(property="transfer_cause", type="string", example="買賣", description="轉移原因"),
     *                 @OA\Property(
     *                     property="transfer_item",
     *                     type="array",
     *                     @OA\Items(type="boolean", example=false),
     *                     description="轉移項目"
     *                 ),
     *                 @OA\Property(property="inhabitant", type="string", example="87789, 法人9", description="立約人姓名"),
     *                 @OA\Property(property="original_inhabitant", type="string", example="87789, 加更", description="原立約人姓名")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="參數錯誤"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="無效的 Token 或無法識別的資料，登入失敗"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="使用已被禁止的 Token 或嘗試訪問權限不足的項目"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="資源不存在，查無資料"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="程式錯誤"
     *     )
     * )
     */
    public function index()
    {
    }

    /**
     * @OA\Post(
     *     path="/household-type/{spaceid}",
     *     tags={"Household-Type 專有空間-戶別列表-立約人資料"},
     *     summary="變更產權資訊",
     *     description="變更產權資訊",
     *     security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *     @OA\Parameter(
     *         name="spaceid",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property(property="sign_date", type="string", example="1120526(民國年)", description="簽約日期"),
     *                 @OA\Property(property="transfer_date", type="string", example="1120526(民國年)", description="異動日期"),
     *                 @OA\Property(property="build_date", type="string", example="1120526(民國年)", description="建置日期"),
     *                 @OA\Property(property="transfer_cause", type="string", example="買賣",
     *                      enum={"買賣", "贈予", "繼承", "分割繼承","退戶"}, description="變更原因"),
     *                 @OA\Property(property="transfer_item[0]", type="boolean", example=false, description="移轉項目[土地]"),
     *                 @OA\Property(property="transfer_item[1]", type="boolean", example=true, description="移轉項目[建物]"),
     *                 @OA\Property(property="transfer_item[2]", type="boolean", example=false, description="移轉項目[車位]"),
     *                 @OA\Property(property="[file_id][0]", type="string", example="29ec6691-0a90-4700-a890-bf8c59f437bf", description="檔案 UUID"),
     *
     *                 @OA\Property(property="relationship[0][type]", type="string", example="個人",
     *                          enum ={
     *                             "個人", "法人"
     *                            }),
     *                 @OA\Property(property="relationship[0][client_mode]", type="string", example="'所有權人', '立約人'",
     *                     enum ={
     *                            "所有權人", "主要聯絡人", "立約人", "保證人", "貸款人",  "繳款人", "股東", "委任關係","同戶關係人"
     *                           }, description="基本資訊[身分]"),
     *
     *                 @OA\Property(property="relationship[0][portion_percent]", type="integer", example=10000, description="基本資訊[持分比]"),
     *                 @OA\Property(property="relationship[0][portion_area]", type="integer", example=100, description="基本資訊[持分平方公尺]"),
     *                 @OA\Property(property="relationship[0][portion_square_meter]", type="integer", example=87, description="基本資訊[持分坪數]"),
     *
     *                 @OA\Property(property="relationship[0][identity_number]", type="string", example="X181165405", description="基本資訊[身分證字號]"),
     *                 @OA\Property(property="relationship[0][birthday]", type="string", format="date", example="0000-00-00", description="基本資訊[出生日期]"),
     *                 @OA\Property(property="relationship[0][name]", type="string", example="王81GG", description="基本資訊[姓名]"),
     *                 @OA\Property(property="relationship[0][sex]", type="integer", example=2, description="基本資訊[性別]"),
     *                 @OA\Property(property="relationship[0][life]", type="integer", example=0, description="基本資訊[存歿]"),
     *                 @OA\Property(property="relationship[0][account]", type="string", example=09878728, description="基本資訊[註冊APP手機號碼]"),
     *                 @OA\Property(property="relationship[0][basic_remark]", type="string", example="1o4", description="基本資訊[備註]"),
     *
     *                 @OA\Property(property="relationship[0][mailing_address]", type="string", example="台中市公益路39號78278", description="詳細資料[通訊地址]"),
     *                 @OA\Property(property="relationship[0][residence_address]", type="string", description="詳細資料[戶籍地址]"),
     *                 @OA\Property(property="relationship[0][transfer_account]", type="string", description="詳細資料[可能轉帳帳號]"),
     *                 @OA\Property(property="relationship[0][occupation]", type="string", description="職業資訊[職業]"),
     *                 @OA\Property(property="relationship[0][employer]", type="string", description="職業資訊[服務機關]"),
     *                 @OA\Property(property="relationship[0][occupation_remark]", description="職業資訊[備註]"),
     *
     *                 @OA\Property(property="relationship[1][type]", type="string", example="法人",
     *                           enum ={"個人", "法人"}),
     *                  @OA\Property(property="relationship[1][client_mode]", type="string", example="'所有權人', '立約人'",
     *                      enum ={
     *                             "所有權人", "主要聯絡人", "立約人", "保證人", "貸款人",  "繳款人", "股東", "委任關係","同戶關係人"
     *                            }, description="基本資訊[身分]"),
     *                  @OA\Property(property="relationship[1][company_type]", type="string", example="public",
     *                       enum ={"public", "privacy"}, description="基本資訊[公司類別] 公法人:public 私法人:privacy"),
     *
     *                  @OA\Property(property="relationship[1][portion_percent]", type="integer", example=10000, description="基本資訊[持分比]"),
     *                  @OA\Property(property="relationship[1][portion_area]", type="integer", example=100, description="基本資訊[持分平方公尺]"),
     *                  @OA\Property(property="relationship[1][portion_square_meter]", type="integer", example=87, description="基本資訊[持分坪數]"),
     *                  @OA\Property(property="relationship[1][identity_number]", type="string", example="X181165405", description="基本資訊[負責人身分證]"),
     *                  @OA\Property(property="relationship[1][sex]", type="integer", example=2, description="基本資訊[性別]"),
     *                  @OA\Property(property="relationship[1][life]", type="integer", example=0, description="基本資訊[存歿]"),
     *                  @OA\Property(property="relationship[1][company_name]", type="string", example="憶及科技", description="基本資訊[公司名稱]"),
     *                  @OA\Property(property="relationship[1][company_representative]", type="string", example="張先生", description="基本資訊[公司負責人]"),
     *                  @OA\Property(property="relationship[1][company_address]", type="string", example="台中市公益路39號78278", description="基本資訊[公司地址]"),
     *                  @OA\Property(property="relationship[1][company_telephone]", type="string", example="0487875487", description="基本資訊[公司電話]"),
     *                  @OA\Property(property="relationship[1][company_number]", type="string", example="04888888", description="基本資訊[公司統一邊號]"),
     *                  @OA\Property(property="relationship[1][basic_remark]", type="string", description="基本資訊[備註]"),
     *                  @OA\Property(property="relationship[1][company_url]", type="string", description="詳細資料[公司網址]"),
     *
     *                  @OA\Property(property="relationship[1][file_id][0]", type="string", example="29ec6691-0a90-4700-a890-bf8c59f437bf", description="檔案 UUID"),
     *
     *                 @OA\Property(property="contact[0][phone][0][value]", type="string", example="0000111444", description="詳細資料[手機號碼]"),
     *                 @OA\Property(property="contact[0][phone][0][is_send]", type="boolean", example=false, description="詳細資料[發簡訊通知用]"),
     *                 @OA\Property(property="contact[0][phone][1][value]", type="string", example="01111111"),
     *                 @OA\Property(property="contact[0][phone][1][is_send]", type="boolean", example=false),
     *
     *                 @OA\Property(property="contact[0][telephone]", type="string", example="0423829100", description="詳細資料[市內電話]"),
     *                 @OA\Property(property="contact[0][email][0][value]", type="string", example="bcd@gmail.com", description="詳細資料[電子信箱]"),
     *                 @OA\Property(property="contact[0][email][0][is_send]", type="boolean", example=false, description="詳細資料[發簡電子郵件用]"),
     *                 @OA\Property(property="contact[0][email_backup]", type="string", example="amywork0103@gmail.com", description="詳細資料[電子信箱-備用]"),
     *
     *                 @OA\Property(property="salutation[0][0][identity_number]", type="string", example="X181165405", description="配偶&親屬資訊[身分證字號]"),
     *                 @OA\Property(property="salutation[0][0][birthday]", type="string", format="date", example="0000-00-00", description="配偶&親屬資訊[身分證字號]"),
     *                 @OA\Property(property="salutation[0][0][name]", type="string", example="123", description="配偶&親屬資訊[姓名]"),
     *                 @OA\Property(property="salutation[0][0][sex]", type="integer", example=2, description="配偶&親屬資訊[性別]"),
     *                 @OA\Property(property="salutation[0][0][life]", type="integer", example=0, description="配偶&親屬資訊[存歿]"),
     *                 @OA\Property(property="salutation[0][0][basic_remark]", type="string", example="1o4", description="配偶&親屬資訊[備註]"),
     *                 @OA\Property(property="salutation[0][0][is_spouse]", type="boolean", example=true, description="配偶&親屬資訊[是否為配偶]"),
     *                 @OA\Property(property="salutation[0][0][salutation]", type="string", example="外來種生物", description="配偶&親屬資訊[稱謂]"),
     *                 @OA\Property(property="salutation[0][0][phone][0][value]", type="string", example="0000111444", description="配偶&親屬資訊[手機號碼]"),
     *                 @OA\Property(property="salutation[0][0][phone][0][is_send]", type="boolean", example=false, description="配偶&親屬資訊[發簡訊通知用]"),
     *                 @OA\Property(property="salutation[0][0][telephone]", type="string", example="0423829100", description="配偶&親屬資訊[市內電話]"),
     *                 @OA\Property(property="salutation[0][0][email][0][value]", type="string", example="bcd@gmail.com", description="配偶&親屬資訊[市內電話]"),
     *                 @OA\Property(property="salutation[0][0][email][0][is_send]", type="boolean", example=false, description="配偶&親屬資訊[發簡電子郵件用]"),
     *                 @OA\Property(property="salutation[0][0][email_backup]", type="string", example="amywork0103@gmail.com", description="配偶&親屬資訊[電子信箱-備用]"),
     *
     *                 @OA\Property(property="introducer[0][identity_number]", type="string", example="X181165405", description="介紹人-基本資訊[身分證字號]"),
     *                 @OA\Property(property="introducer[0][birthday]", type="string", format="date", example="0000-00-00", description="基本資訊[出生日期]"),
     *                 @OA\Property(property="introducer[0][name]", type="string", example="王81GG", description="基本資訊[姓名]"),
     *                 @OA\Property(property="introducer[0][sex]", type="integer", example=2, description="基本資訊[性別]"),
     *                 @OA\Property(property="introducer[0][life]", type="integer", example=0, description="基本資訊[存歿]"),
     *                 @OA\Property(property="introducer[0][account]", type="string", example=09878728, description="基本資訊[註冊APP手機號碼]"),
     *                 @OA\Property(property="introducer[0][basic_remark]", type="string", example="1o4", description="基本資訊[備註]"),
     *                 @OA\Property(property="introducer[0][mailing_address]", type="string", example="台中市公益路39號78278", description="詳細資料[通訊地址]"),
     *                 @OA\Property(property="introducer[0][residence_address]", type="string", description="詳細資料[戶籍地址]"),
     *                 @OA\Property(property="introducer[0][transfer_account]", type="string", description="詳細資料[可能轉帳帳號]"),
     *                 @OA\Property(property="introducer[0][occupation]", type="string", description="職業資訊[職業]"),
     *                 @OA\Property(property="introducer[0][employer]", type="string", description="職業資訊[服務機關]"),
     *                 @OA\Property(property="introducer[0][tax_id]", type="string", description="職業資訊[公司統編]"),
     *                 @OA\Property(property="introducer[0][company_address]", type="string", description="職業資訊[公司地址]"),
     *                 @OA\Property(property="introducer[0][occupation_remark]", description="職業資訊[備註]"),
     *                 @OA\Property(property="introducer[0][community]", description="基本資訊[現住社區名稱]"),
     *                 @OA\Property(property="introducer[0][construction_company]", description="基本資訊[建設公司名稱]"),
     *                 @OA\Property(property="introducer[0][housing_situation]", description="基本資訊[房屋情況] 所有權人:1 承租:2"),
     *                 @OA\Property(property="introducer[0][phone][0][value]", type="string", example="0000111444", description="[手機號碼]"),
     *                 @OA\Property(property="introducer[0][phone][0][is_send]", type="boolean", example=false, description="[發簡訊通知用]"),
     *                 @OA\Property(property="introducer[0][telephone]", type="string", example="0423829100", description="[市內電話]"),
     *                 @OA\Property(property="introducer[0][email][0][value]", type="string", example="bcd@gmail.com", description="[市內電話]"),
     *                 @OA\Property(property="introducer[0][email][0][is_send]", type="boolean", example=false, description="[發簡電子郵件用]"),
     *                 @OA\Property(property="introducer[0][email_backup]", type="string", example="amywork0103@gmail.com", description="[電子信箱-備用]"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *          @OA\Response(
     *          response=301,
     *          description="網址跳轉"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="參數錯誤"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="無效的 Token、或是無法識別的資料、登入失敗"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="資源不存在，查無資料"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="程式錯誤"
     *      )
     *  )
     * )
     *
     * */
    public function store()
    {
    }

    /**
     * @OA\Get(
     *     path="/household-type/{spaceid}/{id}",
     *     tags={"Household-Type 專有空間-戶別列表-立約人資料"},
     *     summary="獲取產權資訊編輯資料",
     *     description="根據空間 ID 和戶籍類型 ID 獲取特定戶籍類型資料",
     *     security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *     @OA\Parameter(
     *         name="spaceid",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="取得成功",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="取得成功"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="property_info",
     *                     type="object",
     *                     @OA\Property(property="sign_date", type="string", example="1120526"),
     *                     @OA\Property(property="transfer_date", type="string", example="1120526"),
     *                     @OA\Property(property="build_date", type="string", example="1120526"),
     *                     @OA\Property(property="transfer_cause", type="string", example="買賣"),
     *                     @OA\Property(
     *                         property="transfer_item",
     *                         type="array",
     *                         @OA\Items(type="boolean", example=false)
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="property_transaction_info",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="client_id", type="string", example="86053e4b-400c-41ad-a1a1-519117e1f502"),
     *                         @OA\Property(
     *                             property="mode",
     *                             type="array",
     *                             @OA\Items(type="string", example="所有權人")
     *                         ),
     *                         @OA\Property(property="portion_percent", type="string", example="10000"),
     *                         @OA\Property(property="portion_area", type="integer", example=100),
     *                         @OA\Property(property="portion_square_meter", type="integer", example=87),
     *                         @OA\Property(property="type", type="string", example="個人")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="relationship",
     *                     type="array",
     *                     description="立約人所有權人資料",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="client_id", type="string", example="60f38feb-9b30-41c3-aba3-fac50b44bc6c"),
     *                         @OA\Property(property="name", type="string", example="加更"),
     *                         @OA\Property(property="sex", type="integer", example=2),
     *                         @OA\Property(property="birthday", type="string", example="1130606"),
     *                         @OA\Property(property="identity_number", type="string", example="X181165406"),
     *                         @OA\Property(property="mailing_address", type="string", nullable=true),
     *                         @OA\Property(property="residence_address", type="string", nullable=true),
     *                         @OA\Property(property="transfer_account", type="string", nullable=true),
     *                         @OA\Property(property="occupation", type="string", nullable=true),
     *                         @OA\Property(property="employer", type="string", nullable=true),
     *                         @OA\Property(property="basic_remark", type="string", example="1o4"),
     *                         @OA\Property(property="occupation_remark", type="string", nullable=true),
     *                         @OA\Property(property="life", type="integer", example=0),
     *                         @OA\Property(
     *                             property="client_contact",
     *                             type="array",
     *                             @OA\Items(
     *                                 type="object",
     *                                 @OA\Property(property="id", type="integer", example=1831),
     *                                 @OA\Property(property="client_id", type="string", example="60f38feb-9b30-41c3-aba3-fac50b44bc6c"),
     *                                 @OA\Property(property="type", type="string", example="email_backup"),
     *                                 @OA\Property(property="value", type="string", example="amywork0103@gmail.com"),
     *                                 @OA\Property(property="is_send", type="boolean", example=false)
     *                             )
     *                         )
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="salutation",
     *                     type="array",
     *                     description="親屬配偶資訊",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="client_id", type="string", example="86053e4b-400c-41ad-a1a1-519117e1f502"),
     *                         @OA\Property(property="related_client_id", type="string", example="60f38feb-9b30-41c3-aba3-fac50b44bc6c"),
     *                         @OA\Property(property="salutation", type="string", example="配偶"),
     *                         @OA\Property(property="is_spouse", type="boolean", example=true),
     *                         @OA\Property(property="name", type="string", example="87789"),
     *                         @OA\Property(property="sex", type="integer", example=2),
     *                         @OA\Property(property="birthday", type="string", example="1130606"),
     *                         @OA\Property(property="identity_number", type="string", example="X181165405"),
     *                         @OA\Property(property="mailing_address", type="string", example="台中市公益路39號78278"),
     *                         @OA\Property(property="residence_address", type="string", nullable=true),
     *                         @OA\Property(property="transfer_account", type="string", nullable=true),
     *                         @OA\Property(property="occupation", type="string", nullable=true),
     *                         @OA\Property(property="employer", type="string", nullable=true),
     *                         @OA\Property(property="basic_remark", type="string", example="1o488"),
     *                         @OA\Property(property="occupation_remark", type="string", nullable=true),
     *                         @OA\Property(property="life", type="integer", example=0),
     *                         @OA\Property(
     *                             property="contact",
     *                             type="array",
     *                             @OA\Items(
     *                                 type="object",
     *                                 @OA\Property(property="id", type="integer", example=1823),
     *                                 @OA\Property(property="client_id", type="string", example="86053e4b-400c-41ad-a1a1-519117e1f502"),
     *                                 @OA\Property(property="type", type="string", example="email_backup"),
     *                                 @OA\Property(property="value", type="string", example="amywork0103@gmail.com"),
     *                                 @OA\Property(property="is_send", type="boolean", example=false)
     *                             )
     *                         )
     *                     )
     *                 ),
     *                 @OA\Property(
     *                      property="introducer",
     *                      type="array",
     *                      description="介紹人資料",
     *                      @OA\Items(
     *                          type="object",
     *                          @OA\Property(property="client_id", type="string", example="86053e4b-400c-41ad-a1a1-519117e1f502"),
     *                          @OA\Property(property="name", type="string", example="黃先生"),
     *                          @OA\Property(property="sex", type="integer", example=2),
     *                          @OA\Property(property="birthday", type="string", example="1130606"),
     *                          @OA\Property(property="identity_number", type="string", example="X181165405"),
     *                          @OA\Property(property="mailing_address", type="string", example="台中市公益路39號78278"),
     *                          @OA\Property(property="residence_address", type="string", nullable=true),
     *                          @OA\Property(property="transfer_account", type="string", nullable=true),
     *                          @OA\Property(property="occupation", type="string", nullable=true),
     *                          @OA\Property(property="employer", type="string", nullable=true),
     *                          @OA\Property(property="basic_remark", type="string", example="1o488"),
     *                          @OA\Property(property="occupation_remark", type="string", nullable=true),
     *                          @OA\Property(property="life", type="integer", example=0),
     *                          @OA\Property(property="community", type="string", example="DEMO展示社區"),
     *                          @OA\Property(property="id", type="integer", example=0),
     *                          @OA\Property(property="housing_situation", type="integer", example="1"),
     *                          @OA\Property(property="construction_company", type="string", example="憶及哭季"),
     *                          @OA\Property(
     *                              property="contact",
     *                              type="array",
     *                              @OA\Items(
     *                                  type="object",
     *                                  @OA\Property(property="id", type="integer", example=1823),
     *                                  @OA\Property(property="client_id", type="string", example="86053e4b-400c-41ad-a1a1-519117e1f502"),
     *                                  @OA\Property(property="type", type="string", example="email_backup"),
     *                                  @OA\Property(property="value", type="string", example="amywork0103@gmail.com"),
     *                                  @OA\Property(property="is_send", type="boolean", example=false)
     *                              )
     *                          )
     *                      )
     *                  )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="參數錯誤"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="無效的 Token 或無法識別的資料，登入失敗"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="使用已被禁止的 Token 或嘗試訪問權限不足的項目"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="資源不存在，查無資料"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="程式錯誤"
     *     )
     * )
     */
    public function show()
    {
    }

    /**
     * @OA\Patch(
     *     path="/household-type/{spaceid}/{id}",
     *     tags={"Household-Type 專有空間-戶別列表-立約人資料"},
     *     summary="變更產權資訊",
     *     description="變更產權資訊",
     *     security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *     @OA\Parameter(
     *         name="spaceid",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="string")
     *      ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property(property="sign_date", type="string", example="1120526", description="簽約日期"),
     *                 @OA\Property(property="transfer_date", type="string", example="1120526", description="異動日期"),
     *                 @OA\Property(property="build_date", type="string", example="1120526", description="建置日期"),
     *                 @OA\Property(property="transfer_cause", type="string", example="買賣",
     *                      enum={"買賣", "贈予", "繼承", "分割繼承","退戶"}, description="變更原因"),
     *                 @OA\Property(property="transfer_item[0]", type="boolean", example=false, description="移轉項目[土地]"),
     *                 @OA\Property(property="transfer_item[1]", type="boolean", example=true, description="移轉項目[建物]"),
     *                 @OA\Property(property="transfer_item[2]", type="boolean", example=false, description="移轉項目[車位]"),
     *                 @OA\Property(property="file_id[0]", type="string", example="29ec6691-0a90-4700-a890-bf8c59f437bf", description="檔案 UUID 有新增再給"),
     *
     *                 @OA\Property(property="relationship[0][client_id]", type="string", example="be7b9f79-3f37-4b36-b289-49bf1fc3ea8a", description="基本資訊[立約人client_id] 新增給Null"),
     *                 @OA\Property(property="relationship[0][type]", type="string", example="個人",
     *                         enum ={
     *                              "個人", "法人"
     *                             }),
     *                 @OA\Property(property="relationship[0][client_mode]", type="string", example="'所有權人', '立約人'",
     *                     enum ={
     *                            "所有權人", "主要聯絡人", "立約人", "保證人", "貸款人",  "繳款人", "股東", "委任關係","同戶關係人"
     *                           }, description="基本資訊[身分]"),
     *
     *                 @OA\Property(property="relationship[0][portion_percent]", type="integer", example=10000, description="基本資訊[持分比]"),
     *                 @OA\Property(property="relationship[0][portion_area]", type="integer", example=100, description="基本資訊[持分平方公尺]"),
     *                 @OA\Property(property="relationship[0][portion_square_meter]", type="integer", example=87, description="基本資訊[持分坪數]"),
     *
     *                 @OA\Property(property="relationship[0][identity_number]", type="string", example="X181165405", description="基本資訊[身分證字號]"),
     *                 @OA\Property(property="relationship[0][birthday]", type="string", format="date", example="0000-00-00", description="基本資訊[出生日期]"),
     *                 @OA\Property(property="relationship[0][name]", type="string", example="王81GG", description="基本資訊[姓名]"),
     *                 @OA\Property(property="relationship[0][sex]", type="integer", example=2, description="基本資訊[性別]"),
     *                 @OA\Property(property="relationship[0][life]", type="integer", example=0, description="基本資訊[存歿]"),
     *                 @OA\Property(property="relationship[0][account]", type="string", example=09878728, description="基本資訊[註冊APP手機號碼]"),
     *                 @OA\Property(property="relationship[0][basic_remark]", type="string", example="1o4", description="基本資訊[備註]"),
     *
     *                 @OA\Property(property="relationship[0][mailing_address]", type="string", example="台中市公益路39號78278", description="詳細資料[通訊地址]"),
     *                 @OA\Property(property="relationship[0][residence_address]", type="string", description="詳細資料[戶籍地址]"),
     *                 @OA\Property(property="relationship[0][transfer_account]", type="string", description="詳細資料[可能轉帳帳號]"),
     *                 @OA\Property(property="relationship[0][occupation]", type="string", description="職業資訊[職業]"),
     *                 @OA\Property(property="relationship[0][employer]", type="string", description="職業資訊[服務機關]"),
     *                 @OA\Property(property="relationship[0][occupation_remark]", description="職業資訊[備註]"),
     *
     *                 @OA\Property(property="relationship[1][type]", type="string", example="法人",
     *                            enum ={"個人", "法人"}),
     *                 @OA\Property(property="relationship[1][client_mode]", type="string", example="'所有權人', '立約人'",
     *                       enum ={
     *                              "所有權人", "主要聯絡人", "立約人", "保證人", "貸款人",  "繳款人", "股東", "委任關係","同戶關係人"
     *                             }, description="基本資訊[身分]"),
     *                 @OA\Property(property="relationship[1][client_id]", type="string", example="be7b9f79-3f37-4b36-b289-49bf1fc3ea8a", description="基本資訊[立約人client_id] 新增給Null"),
     *                 @OA\Property(property="relationship[1][client_company_id]", type="string", example="f844db61-8eda-4aae-bba0-1561027282ff", description="基本資訊[公司資訊uuid] 新增給Null"),
     *                 @OA\Property(property="relationship[1][company_type]", type="string", example="public",
     *                        enum ={"public", "privacy"}, description="基本資訊[公司類別] 公法人:public 私法人:privacy"),
     *                 @OA\Property(property="relationship[1][portion_percent]", type="integer", example=10000, description="基本資訊[持分比]"),
     *                 @OA\Property(property="relationship[1][portion_area]", type="integer", example=100, description="基本資訊[持分平方公尺]"),
     *                 @OA\Property(property="relationship[1][portion_square_meter]", type="integer", example=87, description="基本資訊[持分坪數]"),
     *                 @OA\Property(property="relationship[1][identity_number]", type="string", example="X181165405", description="基本資訊[負責人身分證]"),
     *                 @OA\Property(property="relationship[1][sex]", type="integer", example=2, description="基本資訊[性別] 1 男 2 女 "),
     *                 @OA\Property(property="relationship[1][life]", type="integer", example=0, description="基本資訊[存歿]"),
     *                 @OA\Property(property="relationship[1][company_name]", type="string", example="憶及科技", description="基本資訊[公司名稱]"),
     *                 @OA\Property(property="relationship[1][company_representative]", type="string", example="張先生", description="基本資訊[公司負責人]"),
     *                 @OA\Property(property="relationship[1][del_file_id][0]", type="string", example="f844db61-8eda-4aae-bba0-1561027282ff", description="基本資訊[刪處檔案]"),
     *                 @OA\Property(property="relationship[1][company_address]", type="string", example="台中市公益路39號78278", description="基本資訊[公司地址]"),
     *                 @OA\Property(property="relationship[1][company_telephone]", type="string", example="0487875487", description="基本資訊[公司電話]"),
     *                 @OA\Property(property="relationship[1][company_number]", type="string", example="04888888", description="基本資訊[公司統一邊號]"),
     *                 @OA\Property(property="relationship[1][basic_remark]", type="string", description="基本資訊[備註]"),
     *                 @OA\Property(property="relationship[1][company_url]", type="string", description="詳細資料[公司網址]"),
     *
     *                 @OA\Property(property="relationship[1][file_id][0]", type="string", example="29ec6691-0a90-4700-a890-bf8c59f437bf", description="檔案 UUID 有新增再給"),
     *                 @OA\Property(property="contact[0][phone][0][value]", type="string", example="0000111444", description="詳細資料[手機號碼]"),
     *                 @OA\Property(property="contact[0][phone][0][is_send]", type="boolean", example=false, description="詳細資料[發簡訊通知用]"),
     *                 @OA\Property(property="contact[0][phone][1][value]", type="string", example="01111111"),
     *                 @OA\Property(property="contact[0][phone][1][is_send]", type="boolean", example=false),
     *
     *                 @OA\Property(property="contact[0][telephone]", type="string", example="0423829100", description="詳細資料[市內電話]"),
     *                 @OA\Property(property="contact[0][email][0][value]", type="string", example="bcd@gmail.com", description="詳細資料[電子信箱]"),
     *                 @OA\Property(property="contact[0][email][0][is_send]", type="boolean", example=false, description="詳細資料[發簡電子郵件用]"),
     *                 @OA\Property(property="contact[0][email_backup]", type="string", example="amywork0103@gmail.com", description="詳細資料[電子信箱-備用]"),
     *
     *                 @OA\Property(property="salutation[0][0][identity_number]", type="string", example="X181165405", description="配偶&親屬資訊[身分證字號]"),
     *                 @OA\Property(property="salutation[0][0][birthday]", type="string", format="date", example="0000-00-00", description="配偶&親屬資訊[身分證字號]"),
     *                 @OA\Property(property="salutation[0][0][name]", type="string", example="123", description="配偶&親屬資訊[姓名]"),
     *                 @OA\Property(property="salutation[0][0][sex]", type="integer", example=2, description="配偶&親屬資訊[性別]"),
     *                 @OA\Property(property="salutation[0][0][life]", type="integer", example=0, description="配偶&親屬資訊[存歿]"),
     *                 @OA\Property(property="salutation[0][0][basic_remark]", type="string", example="1o4", description="配偶&親屬資訊[備註]"),
     *                 @OA\Property(property="salutation[0][0][is_spouse]", type="boolean", example=true, description="配偶&親屬資訊[是否為配偶]"),
     *                 @OA\Property(property="salutation[0][0][salutation]", type="string", example="外來種生物", description="配偶&親屬資訊[稱謂]"),
     *                 @OA\Property(property="salutation[0][0][phone][0][value]", type="string", example="0000111444", description="配偶&親屬資訊[手機號碼]"),
     *                 @OA\Property(property="salutation[0][0][phone][0][is_send]", type="boolean", example=false, description="配偶&親屬資訊[發簡訊通知用]"),
     *                 @OA\Property(property="salutation[0][0][telephone]", type="string", example="0423829100", description="配偶&親屬資訊[市內電話]"),
     *                 @OA\Property(property="salutation[0][0][email][0][value]", type="string", example="bcd@gmail.com", description="配偶&親屬資訊[市內電話]"),
     *                 @OA\Property(property="salutation[0][0][email][0][is_send]", type="boolean", example=false, description="配偶&親屬資訊[發簡電子郵件用]"),
     *                 @OA\Property(property="salutation[0][0][email_backup]", type="string", example="amywork0103@gmail.com", description="配偶&親屬資訊[電子信箱-備用]"),
     *                  @OA\Property(property="introducer[0][identity_number]", type="string", example="X181165405", description="介紹人-基本資訊[身分證字號]"),
     *                  @OA\Property(property="introducer[0][client_id]", type="string", example="80fb0285-c625-4964-a22f-b8968a16216c", description="基本資訊[介紹人client_id] 新增給Null"),
     *                  @OA\Property(property="introducer[0][birthday]", type="string", format="date", example="0000-00-00", description="基本資訊[出生日期]"),
     *                  @OA\Property(property="introducer[0][name]", type="string", example="王81GG", description="基本資訊[姓名]"),
     *                  @OA\Property(property="introducer[0][sex]", type="integer", example=2, description="基本資訊[性別]"),
     *                  @OA\Property(property="introducer[0][life]", type="integer", example=0, description="基本資訊[存歿]"),
     *                  @OA\Property(property="introducer[0][account]", type="string", example=09878728, description="基本資訊[註冊APP手機號碼]"),
     *                  @OA\Property(property="introducer[0][basic_remark]", type="string", example="1o4", description="基本資訊[備註]"),
     *                  @OA\Property(property="introducer[0][mailing_address]", type="string", example="台中市公益路39號78278", description="詳細資料[通訊地址]"),
     *                  @OA\Property(property="introducer[0][residence_address]", type="string", description="詳細資料[戶籍地址]"),
     *                  @OA\Property(property="introducer[0][transfer_account]", type="string", description="詳細資料[可能轉帳帳號]"),
     *                  @OA\Property(property="introducer[0][occupation]", type="string", description="職業資訊[職業]"),
     *                  @OA\Property(property="introducer[0][employer]", type="string", description="職業資訊[服務機關]"),
     *                  @OA\Property(property="introducer[0][tax_id]", type="string", description="職業資訊[公司統編]"),
     *                  @OA\Property(property="introducer[0][company_address]", type="string", description="職業資訊[公司地址]"),
     *                  @OA\Property(property="introducer[0][occupation_remark]", type="string",  description="職業資訊[備註]"),
     *                  @OA\Property(property="introducer[0][community]", type="string",description="基本資訊[現住社區名稱]"),
     *                  @OA\Property(property="introducer[0][construction_company]", type="string", description="基本資訊[建設公司名稱]"),
     *                  @OA\Property(property="introducer[0][housing_situation]", type="integer", description="基本資訊[房屋情況] 所有權人:1 承租:2"),
     *                  @OA\Property(property="introducer[0][phone][0][value]", type="string", example="0000111444", description="[手機號碼]"),
     *                  @OA\Property(property="introducer[0][phone][0][is_send]", type="boolean", example=false, description="[發簡訊通知用]"),
     *                  @OA\Property(property="introducer[0][telephone]", type="string", example="0423829100", description="[市內電話]"),
     *                  @OA\Property(property="introducer[0][email][0][value]", type="string", example="bcd@gmail.com", description="[市內電話]"),
     *                  @OA\Property(property="introducer[0][email][0][is_send]", type="boolean", example=false, description="[發簡電子郵件用]"),
     *                  @OA\Property(property="introducer[0][email_backup]", type="string", example="amywork0103@gmail.com", description="[電子信箱-備用]"),
     *                  @OA\Property(property="introducer_del", type="string", example="6,7", description="刪除介紹人"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *          @OA\Response(
     *          response=301,
     *          description="網址跳轉"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="參數錯誤"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="無效的 Token、或是無法識別的資料、登入失敗"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="資源不存在，查無資料"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="程式錯誤"
     *      )
     *  )
     * )
     *
     * */
    public function update()
    {
    }

    /**
     * @OA\Delete(
     *      path="/household-type/{spaceId}",
     *      tags={"Household-Type 專有空間-戶別列表-立約人資料"},
     *      summary="變更產權資訊",
     *      description="變更產權資訊",
     *      security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *      @OA\Parameter(
     *           name="spaceId",
     *           in="path",
     *           required=true,
     *           description="空間 (戶別) UUID",
     *           @OA\Schema(
     *               type="string",
     *               format="uuid"
     *          )
     *      ),
     *      @OA\Parameter(
     *           name="client_id[0]",
     *           description="客戶資料 UUID",
     *           in="query",
     *           required=false,
     *           @OA\Schema(
     *               type="string",
     *               format="uuid"
     *          )
     *      ),
     *      @OA\Response(response=201, description="建立成功"),
     *      @OA\Response(response=301, description="網址跳轉"),
     *      @OA\Response(response=400, description="參數錯誤"),
     *      @OA\Response(response=401, description="無效的 Token、或是無法識別的資料、登入失敗"),
     *      @OA\Response(response=403, description="使用已經被禁止的 Token 或是嘗試訪問權限不足的項目"),
     *      @OA\Response(response=404, description="資源不存在，查無資料"),
     *      @OA\Response(response=500, description="程式錯誤")
     * )
     */
    public function destroy()
    {
    }

    /**
     * @OA\Get(
     *     path="/household-type/{id}/identity/number",
     *     tags={"Household-Type 專有空間-戶別列表-立約人資料"},
     *     summary="根據身份證字號獲取 用戶資料",
     *     description="根據身份證字號獲取特定用戶資料",
     *     security={{"Authorization": {}}, {"Community-Id-Header": {}}},
     *     @OA\Parameter(
     *         name="spaceId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="identity_number",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="取得成功",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="取得成功"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="string", example="2b690fed-91b9-4f23-9e8f-387ff9b3e568"),
     *                 @OA\Property(property="company_id", type="integer", example=10),
     *                 @OA\Property(property="account", type="string", example=""),
     *                 @OA\Property(property="name", type="string", example="法人9"),
     *                 @OA\Property(property="sex", type="integer", example=1),
     *                 @OA\Property(property="birthday", type="string", nullable=true),
     *                 @OA\Property(property="identity_number", type="string", example="X181165405"),
     *                 @OA\Property(property="mailing_address", type="string", nullable=true),
     *                 @OA\Property(property="residence_address", type="string", nullable=true),
     *                 @OA\Property(property="transfer_account", type="string", nullable=true),
     *                 @OA\Property(property="occupation", type="string", nullable=true),
     *                 @OA\Property(property="employer", type="string", nullable=true),
     *                 @OA\Property(property="basic_remark", type="string", example="1o4"),
     *                 @OA\Property(property="occupation_remark", type="string", nullable=true),
     *                 @OA\Property(property="life", type="integer", example=0),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-06-13T08:12:43.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-06-13T09:43:18.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="參數錯誤"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="無效的 Token 或無法識別的資料，登入失敗"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="使用已被禁止的 Token 或嘗試訪問權限不足的項目"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="資源不存在，查無資料"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="程式錯誤"
     *     )
     * )
     */
    public function fetchIdentityNumber()
    {
    }
}
