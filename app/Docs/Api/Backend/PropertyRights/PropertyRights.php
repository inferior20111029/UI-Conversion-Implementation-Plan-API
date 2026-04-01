<?php

namespace App\Docs\Api\Backend\PropertyRights;

class PropertyRights
{
    /**
     * @OA\Get(
     *      path="/property/rights",
     *      operationId="AllPropertyRights",
     *      tags={"Property Rights 產權"},
     *      summary="取得全部產權資料",
     *      description="取得全部產權資料，請填寫 Community-Id-Header (建案 ID)",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Parameter(name="page", description="頁數", required=false, in="query", @OA\Schema(type="integer", format="integer")),
     *      @OA\Parameter(name="perPage", description="呈現幾筆資料，如果無填寫，呈現 10 筆資料", required=false, in="query", @OA\Schema(type="integer", format="integer")),
     *      @OA\Parameter(
     *          name="districtName",
     *          description="搜尋區名",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="buildingName",
     *          description="搜尋棟別",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="staircaseName",
     *          description="搜尋梯間",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="floorName",
     *          description="搜尋樓層",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="householdName",
     *          description="搜尋戶別 / 公設名稱",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="doorplate",
     *          description="搜尋門牌",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="layoutName",
     *          description="搜尋格局",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="mainApplication",
     *          description="搜尋主要用途",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="live",
     *          description="搜尋居住情況",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="rentalAndSale",
     *          description="搜尋租售狀態",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="price",
     *          description="搜尋建議售價",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer",
     *              format="integer",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="rentPrice",
     *          description="搜尋建議租金",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer",
     *              format="integer",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="certification",
     *          description="搜尋能效標章，0:未上傳、1：已上傳",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer",
     *              format="integer",
     *              enum={"0", "1"}
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="exclusiveAreaTotal",
     *          description="搜尋專有面積小計",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer",
     *              format="integer",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="publicHoldingAreaTotal",
     *          description="搜尋公設面積小計",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer",
     *              format="integer",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="registerArea",
     *          description="搜尋登記面積",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer",
     *              format="integer",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="agreedDedicatedTotal",
     *          description="搜尋約定面積小計",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer",
     *              format="integer",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="landArea",
     *          description="搜尋土地面積",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer",
     *              format="integer",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="landAgreementArea",
     *          description="搜尋約定土地面積",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer",
     *              format="integer",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="取得成功",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  example={
     *                      "code": 200,
     *                      "message": "取得成功",
     *                      "data": {
     *                          "page": "當前頁",
     *                          "perPage": "呈現幾筆資料",
     *                          "total": "資料總數",
     *                          "lastPage": "最後頁數",
     *                          "nextUrl": "下一頁網址",
     *                          "prevUrl": "前一頁網址",
     *                          "list": {
     *                              "spaceId": "戶別 ID",
     *                              "name": "戶別名稱",
     *                              "locate": "坐落",
     *                              "districtName": "區名名稱",
     *                              "buildingName": "棟別名稱",
     *                              "staircaseName": "梯間名稱",
     *                              "floorName": "樓層名稱",
     *                              "blockId": "建號",
     *                              "doorplate": "門牌",
     *                              "extentOfOwnership": "權利範圍",
     *                              "landUseZoning": "土地使用分區",
     *                              "handoverDate": "交屋日期",
     *                              "buildingBuildLicenceId": "建造執照號碼",
     *                              "useLicenseId": "使用執照號碼",
     *                              "layoutSetting": {
     *                                  "id": "格局 ID",
     *                                  "name": "格局名字",
     *                              },
     *                              "spaceLayout": {
     *                                  {
     *                                      "type": "自訂格局-類型，room (房間)、living_room (客廳/餐廳)、kitchen (廚房)、bathroom (衛浴)、balcony (陽台)",
     *                                      "quantity": "自訂格局-數量",
     *                                      "name": "自訂格局-名稱",
     *                                  }
     *                              },
     *                              "haveCertification": "是否有上傳能效標章",
     *                              "certification": {
     *                                  {
     *                                      "name": "標章-名稱",
     *                                      "version": "標章-版本",
     *                                      "type": "標章-認證類型",
     *                                      "applicationAt": "標章-申請日期",
     *                                      "url": {
     *                                          "圖片網址"
     *                                      },
     *                                  }
     *                              },
     *                              "mainApplication": {
     *                                  "code": "用途代號",
     *                                  "name": "用途名稱",
     *                              },
     *                              "priceData": {
     *                                  "price": "建議售價",
     *                                  "rentPrice": "建議租金",
     *                                  "depositPayer": "訂金付款人",
     *                                  "deposit": "訂金",
     *                              },
     *                              "area": {
     *                                  "setting": {
     *                                      "decimalPlace": "面積皆換算至小數點位數"
     *                                  },
     *                                  "land": {
     *                                      "dedicated": "土地專用面積",
     *                                      "agreement": "土地約定專用面積"
     *                                  },
     *                                  "exclusive": {
     *                                      "name": "專有面積設定-面積名稱，indoor (室內面積)、awning (室內雨遮面積)、balcony (室內陽台面積)",
     *                                      "ping": "專有面積設定-坪數",
     *                                      "allowCalculate": "專有面積設定-是否列入計算 0:否,1:是",
     *                                  },
     *                                  "publicHolding": {
     *                                      "constructionNumber": "公設持分面積設定-建號",
     *                                      "ownershipDenominator": "公設持分面積設定-權力範圍-分母",
     *                                      "ownershipMolecular": "公設持分面積設定-權力範圍-分子",
     *                                      "total": "公設持分面積設定-共有總面積",
     *                                  },
     *                                  "agreedDedicated": {
     *                                      "name": "約定專用面積設定-面積名稱",
     *                                      "ping": "約定專用面積設定-面積坪數",
     *                                  },
     *                                  "agreedDedicatedSetting": {
     *                                      "preservation": "約定專用面積設定-保存面積"
     *                                  },
     *                                  "total": {
     *                                      "exclusive": "專有面積小計",
     *                                      "publicHolding": "公設面積小計",
     *                                      "register": "登記面積小計",
     *                                      "agreedDedicated": "約定面積小計",
     *                                  }
     *                              },
     *                              "houseState": {
     *                                  "live": "房屋居住現況，selfOccupied (自住)、rented (已出租)、vacantHouse (空屋)",
     *                                  "rentalAndSale": "房屋租售狀態，rental (出租)、sell (出售)、notYet (無)",
     *                                  "saleStage": "銷售階段，earnestPayment (斡旋金)、deposit (押金)、sale (已出售)",
     *                                  "house": "屋況，standardConfiguration (標配)、roughcast (毛坯)、decoration (裝潢)",
     *                                  "old": "屋齡，如果為 0 代表了新屋",
     *                              },
     *                              "planning": {
     *                                  {
     *                                      "type": "規劃型態-類型，residential (住宅)、detachedHouse (整層住家)、suite (套房)、independentSuite (獨立套房)、store (店面)、officeBuilding (辦公)、residenceOffice (住辦)",
     *                                      "planning": "規劃型態，apartment (公寓)、villa (別墅)、detached (透天厝)、elevatorBuilding (電梯大樓)、store (店面 (店鋪))",
     *                                  }
     *                              },
     *                              "carParking": {
     *                                  {
     *                                      "id": "車位 ID",
     *                                      "districtName": "區名",
     *                                      "buildingName": "棟別",
     *                                      "staircaseName": "梯間",
     *                                      "floorName": "樓層",
     *                                      "householdName": "戶別 / 公設名稱",
     *                                      "number": "車位編號",
     *                                      "carType": "車位種類",
     *                                      "parkingType": "車位類型",
     *                                      "application": "車位法定名稱",
     *                                      "size": "車位尺寸",
     *                                  }
     *                              },
     *                              "document": {
     *                                  {
     *                                      "uuid": "檔案 UUID",
     *                                      "extension": "檔案副檔名",
     *                                      "originalName": "檔案原始名稱",
     *                                      "mimeType": "mime type",
     *                                      "url": "檔案網址"
     *                                  }
     *                              },
     *                              "realEstateAgent": {
     *                                  {
     *                                      "name": "委任房仲-名字",
     *                                      "startTime": "委託開始時間",
     *                                      "endTime": "委託結束時間",
     *                                      "companyName": "公司名稱",
     *                                      "whileSoldOut": "租售出為止 0:否 1:是",
     *                                      "hasEntrust": "是否委託中"
     *                                  }
     *                              },
     *                              "earnestPayment": {
     *                                  {
     *                                      "uuid": "斡旋金 UUID",
     *                                      "payer": "斡旋金-付款人名字",
     *                                      "amountOfMoney": "斡旋金-金額",
     *                                  }
     *                              },
     *                              "alreadyPublish": {
     *                                  "rent": "是否有刊登出租物件，false:否, true:有",
     *                                  "sell": "是否有刊登出售物件，false:否, true:有"
     *                              }
     *                          },
     *                      }
     *                  }
     *              )
     *          }
     *      ),
     *      @OA\Response(
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
     * )
     */
    public function index() {}

    /**
     * @OA\Get(
     *      path="/property/rights/{spaceId}",
     *      operationId="AlonePropertyRights",
     *      tags={"Property Rights 產權"},
     *      summary="取得單筆產權資料",
     *      description="取得單筆產權資料，請填寫 Community-Id-Header (建案 ID)",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Parameter(
     *          name="spaceId",
     *          description="戶別 ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *              format="uuid"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="取得成功",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  example={
     *                      "code": 200,
     *                      "message": "取得成功",
     *                      "data": {
     *                          "page": "當前頁",
     *                          "perPage": "呈現幾筆資料",
     *                          "total": "資料總數",
     *                          "lastPage": "最後頁數",
     *                          "nextUrl": "下一頁網址",
     *                          "prevUrl": "前一頁網址",
     *                          "list": {
     *                              "spaceId": "戶別 ID",
     *                              "name": "戶別名稱",
     *                              "locate": "坐落",
     *                              "districtName": "區名名稱",
     *                              "buildingName": "棟別名稱",
     *                              "staircaseName": "梯間名稱",
     *                              "floorName": "樓層名稱",
     *                              "blockId": "建號",
     *                              "doorplate": "門牌",
     *                              "extentOfOwnership": "權利範圍",
     *                              "landUseZoning": "土地使用分區",
     *                              "handoverDate": "交屋日期",
     *                              "buildingBuildLicenceId": "建造執照號碼",
     *                              "useLicenseId": "使用執照號碼",
     *                              "layoutSetting": {
     *                                  "id": "格局 ID",
     *                                  "name": "格局名字",
     *                              },
     *                              "spaceLayout": {
     *                                  {
     *                                      "type": "自訂格局-類型，room (房間)、living_room (客廳/餐廳)、kitchen (廚房)、bathroom (衛浴)、balcony (陽台)",
     *                                      "quantity": "自訂格局-數量",
     *                                      "name": "自訂格局-名稱",
     *                                  }
     *                              },
     *                              "haveCertification": "是否有上傳能效標章",
     *                              "certification": {
     *                                  {
     *                                      "name": "標章-名稱",
     *                                      "version": "標章-版本",
     *                                      "type": "標章-認證類型",
     *                                      "applicationAt": "標章-申請日期",
     *                                      "url": {
     *                                          "圖片網址"
     *                                      },
     *                                  }
     *                              },
     *                              "mainApplication": {
     *                                  "code": "用途代號",
     *                                  "name": "用途名稱",
     *                              },
     *                              "priceData": {
     *                                  "price": "建議售價",
     *                                  "rentPrice": "建議租金",
     *                                  "depositPayer": "訂金付款人",
     *                                  "deposit": "訂金",
     *                              },
     *                              "area": {
     *                                  "setting": {
     *                                      "decimalPlace": "面積皆換算至小數點位數"
     *                                  },
     *                                  "land": {
     *                                      "dedicated": "土地專用面積",
     *                                      "agreement": "土地約定專用面積"
     *                                  },
     *                                  "exclusive": {
     *                                      "name": "專有面積設定-面積名稱，indoor (室內面積)、awning (室內雨遮面積)、balcony (室內陽台面積)",
     *                                      "ping": "專有面積設定-坪數",
     *                                      "allowCalculate": "專有面積設定-是否列入計算 0:否,1:是",
     *                                  },
     *                                  "publicHolding": {
     *                                      "constructionNumber": "公設持分面積設定-建號",
     *                                      "ownershipDenominator": "公設持分面積設定-權力範圍-分母",
     *                                      "ownershipMolecular": "公設持分面積設定-權力範圍-分子",
     *                                      "total": "公設持分面積設定-共有總面積",
     *                                  },
     *                                  "agreedDedicated": {
     *                                      "name": "約定專用面積設定-面積名稱",
     *                                      "ping": "約定專用面積設定-面積坪數",
     *                                  },
     *                                  "agreedDedicatedSetting": {
     *                                      "preservation": "約定專用面積設定-保存面積"
     *                                  },
     *                                  "total": {
     *                                      "exclusive": "專有面積小計",
     *                                      "publicHolding": "公設面積小計",
     *                                      "register": "登記面積小計",
     *                                      "agreedDedicated": "約定面積小計",
     *                                  }
     *                              },
     *                              "houseState": {
     *                                  "live": "房屋居住現況，selfOccupied (自住)、rented (已出租)、vacantHouse (空屋)",
     *                                  "rentalAndSale": "房屋租售狀態，rental (出租)、sell (出售)、notYet (無)",
     *                                  "saleStage": "銷售階段，earnestPayment (斡旋金)、deposit (押金)、sale (已出售)",
     *                                  "house": "屋況，standardConfiguration (標配)、roughcast (毛坯)、decoration (裝潢)",
     *                                  "old": "屋齡，如果為 0 代表了新屋",
     *                              },
     *                               "planning": {
     *                                  {
     *                                      "type": "規劃型態-類型，residential (住宅)、detachedHouse (整層住家)、suite (套房)、independentSuite (獨立套房)、store (店面)、officeBuilding (辦公)、residenceOffice (住辦)",
     *                                      "planning": "規劃型態，apartment (公寓)、villa (別墅)、detached (透天厝)、elevatorBuilding (電梯大樓)、store (店面 (店鋪))",
     *                                  }
     *                              },
     *                              "carParking": {
     *                                  {
     *                                      "id": "車位 ID",
     *                                      "districtName": "區名",
     *                                      "buildingName": "棟別",
     *                                      "staircaseName": "梯間",
     *                                      "floorName": "樓層",
     *                                      "householdName": "戶別 / 公設名稱",
     *                                      "number": "車位編號",
     *                                      "carType": "車位種類",
     *                                      "parkingType": "車位類型",
     *                                      "application": "車位法定名稱",
     *                                      "size": "車位尺寸",
     *                                  }
     *                              },
     *                              "document": {
     *                                  {
     *                                      "uuid": "檔案 UUID",
     *                                      "extension": "檔案副檔名",
     *                                      "originalName": "檔案原始名稱",
     *                                      "mimeType": "mime type",
     *                                      "url": "檔案網址"
     *                                  }
     *                              },
     *                              "realEstateAgent": {
     *                                  {
     *                                      "name": "委任房仲-名字",
     *                                      "startTime": "委託開始時間",
     *                                      "endTime": "委託結束時間",
     *                                      "companyName": "公司名稱",
     *                                      "whileSoldOut": "租售出為止 0:否 1:是",
     *                                      "hasEntrust": "是否委託中"
     *                                  }
     *                              },
     *                              "earnestPayment": {
     *                                  {
     *                                      "uuid": "斡旋金 UUID",
     *                                      "payer": "斡旋金-付款人名字",
     *                                      "amountOfMoney": "斡旋金-金額",
     *                                  }
     *                              },
     *                              "alreadyPublish": {
     *                                  "rent": "是否有刊登出租物件，false:否, true:有",
     *                                  "sell": "是否有刊登出售物件，false:否, true:有"
     *                              }
     *                          },
     *                      }
     *                  }
     *              )
     *          }
     *      ),
     *      @OA\Response(
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
     * )
     */
    public function show() {}

    /**
     * @OA\Patch(
     *      path="/property/rights/{spaceId}",
     *      operationId="UpdatePropertyRights",
     *      tags={"Property Rights 產權"},
     *      summary="修改單筆產權基本資料",
     *      description="修改單筆產權基本資料，請填寫 Community-Id-Header (建案 ID)",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Parameter(
     *          name="spaceId",
     *          description="空間 ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *              format="uuid"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(property="price", type="integer", format="integer", default="", description="建議售價，最小值：0, 最大值：4000000000"),
     *                  @OA\Property(property="rentPrice", type="integer", format="integer", default="", description="建議租金，最小值：0, 最大值：4000000000"),
     *                  @OA\Property(property="depositPayer", type="string", format="string", default="", description="訂金付款人，最大字元：20"),
     *                  @OA\Property(property="deposit", type="integer", format="integer", default="", description="訂金，最小值：0, 最大值：4000000000"),
     *                  @OA\Property(property="earnestPayment[0][uuid]", type="string", format="uuid", default="", description="斡旋金 UUID，如果不填此項視為建立或是刪除"),
     *                  @OA\Property(property="earnestPayment[0][payer]", type="string", format="string", default="", description="斡旋金-付款人名字，最大字元：20"),
     *                  @OA\Property(property="earnestPayment[0][amountOfMoney]", type="integer", format="integer", default="", description="斡旋金-金額，最大值：4000000000"),
     *                  @OA\Property(property="planning[0][type]", type="string", format="string", default="", description="規劃型態-類型，請填寫：residential (住宅)、detachedHouse (整層住家)、suite (套房)、independentSuite (獨立套房)、store (店面)、officeBuilding (辦公)、residenceOffice (住辦)，如果有 planning[0][planning] 此項必填"),
     *                  @OA\Property(property="planning[0][planning]", type="string", format="string", default="", description="規劃型態，請填寫：apartment (公寓)、villa (別墅)、detached (透天厝)、elevatorBuilding (電梯大樓)、store (店面 (店鋪))，如果有 planning[0][type] 此項必填"),
     *                  @OA\Property(property="state[live]", type="string", format="string", default="", description="房屋居住現況，請填寫：selfOccupied (自住)、rented (已出租)、vacantHouse (空屋)"),
     *                  @OA\Property(property="state[rentalAndSale]", type="string", format="string", default="", description="房屋租售狀態，請填寫：rental (出租)、sell (出售)、notYet (無)"),
     *                  @OA\Property(property="state[house]", type="string", format="string", default="", description="屋況，請填寫：standardConfiguration (標配)、roughcast (毛坯)、decoration (裝潢)"),
     *                  @OA\Property(property="state[old]", type="integer", format="integer", default="", description="屋齡，最小值：0，最大值：250，預想情況假如是新屋就帶 0"),
     *                  @OA\Property(property="state[saleStage]", type="string", format="string", default="", description="銷售階段，請填寫：earnestPayment (斡旋金)、deposit (押金)、sale (已出售)"),
     *                  @OA\Property(property="document[0]", type="string", format="uuid", default="", description="相關文件，檔案 UUID，如果有多個檔案，後端預期收到陣列資料，例：[fileUuid1, fileUuid2, fileUuid3]"),
     *                  @OA\Property(property="crmLayoutSettingId", type="integer", format="integer", default="", description="格局群組 ID，如果 layout 為空此項必填，如果是自訂格局的情況，不要帶此資料"),
     *                  @OA\Property(property="layout[room]", type="integer", format="integer", default="", description="格局-房間，如果 crmLayoutSettingId 為空此項必填，最小值：0，最大值：255"),
     *                  @OA\Property(property="layout[living_room]", type="integer", format="integer", default="", description="格局-客廳/餐廳，如果 crmLayoutSettingId 為空此項必填，最小值：0，最大值：255"),
     *                  @OA\Property(property="layout[kitchen]", type="integer", format="integer", default="", description="格局-廚房，如果 crmLayoutSettingId 為空此項必填，最小值：0，最大值：255"),
     *                  @OA\Property(property="layout[bathroom]", type="integer", format="integer", default="", description="格局-衛浴，如果 crmLayoutSettingId 為空此項必填，最小值：0，最大值：255"),
     *                  @OA\Property(property="layout[balcony]", type="integer", format="integer", default="", description="格局-陽台，如果 crmLayoutSettingId 為空此項必填，最小值：0，最大值：255"),
     *                  @OA\Property(property="areaSetting[decimalPlace]", type="integer", format="integer", default="", description="面積皆換算至小數點第 X 位，最小值：0，最大值：6"),
     *                  @OA\Property(property="landArea[dedicated]", type="integer", format="integer", default="", description="土地面積設定-土地專用面積，最小值：0，最大值：15000000"),
     *                  @OA\Property(property="landArea[agreement]", type="integer", format="integer", default="", description="土地面積設定-土地約定專用面，最小值：0，最大值：15000000"),
     *                  @OA\Property(property="exclusiveArea[indoor][ping]", type="integer", format="integer", default="", description="專有面積設定-室內面積，最小值：0，最大值：15000000"),
     *                  @OA\Property(property="exclusiveArea[awning][ping]", type="integer", format="integer", default="", description="專有面積設定-室內雨遮面積，最小值：0，最大值：15000000"),
     *                  @OA\Property(property="exclusiveArea[balcony][ping]", type="integer", format="integer", default="", description="專有面積設定-室內陽台面積，最小值：0，最大值：15000000"),
     *                  @OA\Property(property="exclusiveArea[balcony][allowCalculate]", type="integer", format="integer", default="", description="是否列入面積計算，0：否、1：是"),
     *                  @OA\Property(property="customExclusiveArea[0][name]", type="string", format="string", default="", description="專有面積設定-自訂-面積名稱，最大字元：255，如果有 customExclusiveArea[0][ping] 此項必填"),
     *                  @OA\Property(property="customExclusiveArea[0][ping]", type="integer", format="integer", default="", description="專有面積設定-自訂-面積坪數，最小值：0，最大值：15000000，如果有 customExclusiveArea[0][name] 此項必填"),
     *                  @OA\Property(property="publicHoldingArea[0][constructionNumber]", type="string", format="string", default="", description="公設持分面積設定-建號，最大字元：255，當有 publicHoldingArea[0] 此項必填"),
     *                  @OA\Property(property="publicHoldingArea[0][total]", type="integer", format="integer", default="", description="公設持分面積設定-共有總面積，最小值：0，最大值：4000000000"),
     *                  @OA\Property(property="publicHoldingArea[0][ownershipDenominator]", type="integer", format="integer", default="", description="公設持分面積設定-權力範圍-分母，最小值：0，最大值：8000000"),
     *                  @OA\Property(property="publicHoldingArea[0][ownershipMolecular]", type="integer", format="integer", default="", description="公設持分面積設定-權力範圍-分子，最小值：0，最大值：8000000"),
     *                  @OA\Property(property="agreedDedicatedAreaSetting[preservation]", type="integer", format="integer", default="", description="約定專用面積設定-保存面積，最小值：0，最大值：15000000"),
     *                  @OA\Property(property="agreedDedicatedArea[0][name]", type="string", format="string", default="", description="約定專用面積設定-自訂-面積名稱，最大字元：255，如果有 agreedDedicatedArea[0] 此項必填"),
     *                  @OA\Property(property="agreedDedicatedArea[0][ping]", type="string", format="string", default="", description="約定專用面積設定-自訂-坪數，，最小值：0，最大值：4000000000"),
     *                  @OA\Property(property="parkingSpace[0]", type="string", format="uuid", default="", description="車位 ID，如果有多筆，後端預期收到的結果大概長這樣：parkingSpace: {車位ID,車位ID-2}"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="修改成功"
     *      ),
     *      @OA\Response(
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
     * )
     */
    public function update() {}

    /**
     * @OA\Get(
     *      path="/property/rights/area",
     *      operationId="PropertyRightsArea",
     *      tags={"Property Rights 產權"},
     *      summary="取得產權面積總覽資料",
     *      description="取得產權面積總覽資料，請填寫 Community-Id-Header (建案 ID)",
     *      security={{"Authorization":{}}, {"Community-Id-Header":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="取得成功",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  example={
     *                      "code": 200,
     *                      "message": "取得成功",
     *                      "data": {
     *                          "exclusive": "專有坪數 (專有面積小計)",
     *                          "exclusiveProportion": "專有坪數-佔比",
     *                          "publicHolding": "公設持分坪數 (公設面積小計)",
     *                          "publicHoldingProportion": "公設持分坪數-佔比",
     *                          "register": "登記坪數 (登記面積小計)",
     *                          "agreedDedicated": "約定評數 (約定面積小計)",
     *                      }
     *                  }
     *              )
     *          }
     *      ),
     *      @OA\Response(
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
     * )
     */
    public function area() {}
}
