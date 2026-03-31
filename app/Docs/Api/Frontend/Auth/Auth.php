<?php

namespace App\Docs\Api\Frontend\Auth;

class Auth
{
    /**
     * @OA\Post(
     *      path="/frontend/auth/login",
     *      operationId="AuthLogin",
     *      tags={"Auth 前台使用者認證"},
     *      summary="取得 Access Token",
     *      description="取得 Access Token",
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={"account", "password"},
     *                  @OA\Property(property="account", type="string", format="string", default="", description="帳號"),
     *                  @OA\Property(property="password", type="string", format="string", default="", description="密碼"),
     *              )
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
     *                      "message": "Access Token 取得成功",
     *                      "data": {
     *                         "accessToken": "Access Token",
     *                         "tokenType": "Token 類型",
     *                         "expiresIn": "過期時間 (時間戳記)",
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
    public function login() {}

    /**
     * @OA\Post(
     *      path="/frontend/auth/me",
     *      operationId="LoginMe",
     *      tags={"Auth 前台使用者認證"},
     *      summary="取得 Access Token 人員資料",
     *      description="取得 Access Token 人員資料",
     *      security={{"Authorization":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="取得成功",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  example={
     *                      "code": 200,
     *                      "message": "Access Token 取得成功",
     *                      "data": {
     *                         "id": "使用者 UUID",
     *                         "account": "帳號",
     *                         "name": "使用者名字",
     *                         "type": "登入人員類型：realEstateAgent 房仲，ps: 之後應該還會多 所有權人 類型之類的"
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
    public function me() {}

    /**
     * @OA\Post(
     *      path="/frontend/auth/logout",
     *      operationId="AuthLogout",
     *      tags={"Auth 前台使用者認證"},
     *      summary="使用者登出",
     *      description="使用者登出",
     *      security={{"Authorization":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="登出成功",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  example={
     *                      "code": 200,
     *                      "message": "登出成功",
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
    public function logout() {}

    /**
     * @OA\Post(
     *      path="/frontend/auth/refresh",
     *      operationId="RefreshToken",
     *      tags={"Auth 前台使用者認證"},
     *      summary="刷新 Access Token",
     *      description="刷新 Access Token",
     *      security={{"Authorization":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="取得成功",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  example={
     *                      "code": 200,
     *                      "message": "Access Token 取得成功",
     *                      "data": {
     *                         "accessToken": "Access Token",
     *                         "tokenType": "Token 類型",
     *                         "expiresIn": "過期時間 (時間戳記)",
     *                      }
     *                  }
     *              )
     *          }
     *       ),
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
    public function refresh() {}
}
