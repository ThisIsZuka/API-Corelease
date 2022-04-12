<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;



/**
 * @OA\Info(
 *      version="1.2.0",
 *      title="L5 OpenApi API-Corelease",
 *      description="L5 Swagger OpenApi description"
 * )
 *
 * 
 * 
 * @OA\Post(
 * path="/API-Corelease/api/Get_Token",
 * summary="Login",
 * description="Get_Token",
 * operationId="Get_Token",
 * tags={"Authentication"},
 *   @OA\RequestBody(
 *      required=true,
 *      description="Customer information",
 *      @OA\JsonContent(
 *         required={"username","password"},
 *         @OA\Property(property="username", type="string", format="string", example="api_ufund"),
 *         @OA\Property(property="password", type="string", format="password", example="U6undp0Rt4l"),
 *      ),
 *   ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * 
 *
 * 
 * @OA\Get(
 * path="/API-Corelease/api/master_prefix",
 * summary="Master Data คำนำหน้าชื่อ",
 * description="คำนำหน้าชื่อ",
 * operationId="Master_prefix",
 * tags={"API Personal Information"},
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/API-Corelease/api/master_nationality",
 * summary="Master Data สัญชาติ",
 * description="สัญชาติ",
 * operationId="master_nationality",
 * tags={"API Personal Information"},
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/API-Corelease/api/master_marital_status",
 * summary="Master Data สถานะสมรส",
 * description="สถานะสมรส",
 * operationId="master_marital_status",
 * tags={"API Personal Information"},
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/API-Corelease/api/master_occupation",
 * summary="Master Data อาชีพ",
 * description="อาชีพ",
 * operationId="master_occupation",
 * tags={"API Personal Information"},
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/API-Corelease/api/master_level_type",
 * summary="Master Data ระดับการศึกษา",
 * description="ระดับการศึกษา",
 * operationId="master_level_type",
 * tags={"API Personal Information"},
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/API-Corelease/api/master_level",
 * summary="Master Data ชั้นปี",
 * description="ชั้นปี",
 * operationId="master_level",
 * tags={"API Personal Information"},
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/API-Corelease/api/master_rerationship_ref",
 * summary="Master Data ความสัมพันธ์",
 * description="ความสัมพันธ์",
 * operationId="master_rerationship_ref",
 * tags={"API Personal Information"},
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/API-Corelease/api/master_branch_type",
 * summary="Master Data ประเภทสาขา",
 * description="ประเภทสาขา",
 * operationId="master_branch_type",
 * tags={"API Branch"},
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/API-Corelease/api/master_setup_company/{BRANCH_TYPE_ID}",
 * summary="Master Data สาขา",
 * description="สาขา",
 * operationId="master_setup_company",
 * tags={"API Branch"},
 * @OA\Parameter(
 *     name="BRANCH_TYPE_ID",
 *     in="path",
 *     description="รหัสประเภทสาขา",
 *     required=false,
 * ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/API-Corelease/api/master_category",
 * summary="Master Data หมวดสินค้า",
 * description="หมวดสินค้า",
 * operationId="master_category",
 * tags={"API Production"},
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/API-Corelease/api/master_brand",
 * summary="Master Data ยี่ห้อสินค้า",
 * description="ยี่ห้อสินค้า",
 * operationId="master_brand",
 * tags={"API Production"},
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/API-Corelease/api/master_series/{BRAND_ID}",
 * summary="Master Data รุ่นสินค้า",
 * description="รุ่นสินค้า",
 * operationId="master_series",
 * tags={"API Production"},
 * @OA\Parameter(
 *     name="BRAND_ID",
 *     in="path",
 *     description="รหัสยี่ห้อ เช่น 9",
 *     required=false,
 * ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/API-Corelease/api/master_sub_series/{SERIES_ID}",
 * summary="Master Data ความจุ",
 * description="ความจุ",
 * operationId="master_sub_series",
 * tags={"API Production"},
 * @OA\Parameter(
 *     name="SERIES_ID",
 *     in="path",
 *     description="รหัสรุ่นสินค้า เช่น 59",
 *     required=false,
 * ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/API-Corelease/api/master_color/{SERIES_ID}",
 * summary="Master Data สี",
 * description="สี",
 * operationId="master_color",
 * tags={"API Production"},
 * @OA\Parameter(
 *     name="SERIES_ID",
 *     in="path",
 *     description="รหัสรุ่นสินค้า เช่น 59",
 *     required=false,
 * ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/API-Corelease/api/master_assets_information",
 * summary="Master Data อุปกรณ์เสริม",
 * description="อุปกรณ์เสริม",
 * operationId="master_assets_information",
 * tags={"API Production"},
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/API-Corelease/api/master_insure/{SERIES_ID}",
 * summary="Master Data บริการคุ้มครองเสริม",
 * description="บริการคุ้มครองเสริม",
 * operationId="master_insure",
 * tags={"API Production"},
 * @OA\Parameter(
 *     name="SERIES_ID",
 *     in="path",
 *     description="รหัสรุ่นสินค้า เช่น 59",
 *     required=false,
 * ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/API-Corelease/api/master_installment",
 * summary="Master Data จำนวนงวด",
 * description="จำนวนงวด",
 * operationId="master_installment",
 * tags={"API INSTALLMENT"},
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/API-Corelease/api/master_province",
 * summary="Master Data จังหวัด",
 * description="จังหวัด",
 * operationId="master_province",
 * tags={"API Address"},
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * 
 * @OA\Get(
 * path="/API-Corelease/api/master_district/{PROVINCE_ID}",
 * summary="Master Data อำเภอ",
 * description="อำเภอ",
 * operationId="master_district",
 * tags={"API Address"},
 * @OA\Parameter(
 *     name="PROVINCE_ID",
 *     in="path",
 *     description="รหัสจังหวัด เช่น 10",
 *     required=false,
 * ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/API-Corelease/api/master_sub_district/{DISTRICT_ID}",
 * summary="Master Data ตำบล",
 * description="ตำบล",
 * operationId="master_sub_district",
 * tags={"API Address"},
 * @OA\Parameter(
 *     name="DISTRICT_ID",
 *     in="path",
 *     description="รหัสอำเภอ เช่น 1001",
 *     required=false,
 * ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * 
 * 
 * @OA\Get(
 * path="/API-Corelease/api/master_university",
 * summary="Master Data มหาวิทยาลัย",
 * description="มหาวิทยาลัย",
 * operationId="Get-master_university",
 * tags={"API University"},
 *  @OA\Parameter(
 *          name="PROVINCE_ID",
 *          in="query",
 *          description="รหัสจังหวัด เช่น 10",
 *          required=false,
 *   ),
 *  @OA\Parameter(
 *          name="DISTRICT_ID",
 *          in="query",
 *          description="รหัสอำเภอ เช่น 1010",
 *          required=false,
 *   ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * 
 * @OA\Get(
 * path="/API-Corelease/api/master_faculty",
 * summary="Master Data คณะ",
 * description="คณะ",
 * operationId="Get-master_faculty",
 * tags={"API University"},
 *  @OA\Parameter(
 *          name="MT_UNIVERSITY_ID",
 *          in="query",
 *          description="รหัสมหาวิทยาลัย เช่น 2",
 *          required=false,
 *   ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * 
 * 
 * 
 * @OA\SecurityScheme(
 *     type="apiKey",
 *     description="Login with username and password to get the authentication token",
 *     name="Api-Token",
 *     in="header",
 *     securityScheme="Api-Token",
 * ),
 * 
 * 
 * 
 * 
 * @OA\Post(
 * path="/API-Corelease/api/new_customer",
 * summary="Insert new customer",
 * description="new custome",
 * operationId="new_customer",
 * tags={"API QUTATATION"},
 * security={{ "Api-Token": {} }},
 *   @OA\RequestBody(
 *      required=true,
 *      description="Customer information",
 *      @OA\JsonContent(
 *         @OA\Property(property="BRANCH_TYPE", type="string", format="string", example="1"),
 *         @OA\Property(property="BRANCH_ID", type="string", format="string", example="31"),
 *         @OA\Property(property="TAX_ID", type="string", format="string", example="4856465441239"),
 *         @OA\Property(property="CUSTOMER_NAME", type="string", format="string", example="TEST API"),
 *         @OA\Property(property="OCCUPATION_ID", type="string", format="string", example="2"),
 *         @OA\Property(property="UNIVERSITY_ID", type="string", format="string", example="13"),
 *         @OA\Property(property="FACULTY_ID", type="string", format="string", example="807"),
 *         @OA\Property(property="PRODUCT_SERIES", type="string", format="string", example="194252038338"),
 *         @OA\Property(property="PROD_PRICE", type="string", format="string", example="35400"),
 *         @OA\Property(property="DOWN_PERCENT", type="string", format="string", example="0.1"),
 *         @OA\Property(property="INSTALL_NUM", type="string", format="string", example="24"),
 *         @OA\Property(property="ACS_ID", type="string", format="string", example="484"),
 *         @OA\Property(property="ACS_DES", type="string", format="string", example="Apple Pencil 2"),
 *         @OA\Property(property="ACS_PRICE", type="string", format="string", example="4190"),
 *         @OA\Property(property="INSURE_ID", type="string", format="string", example="42"),
 *         @OA\Property(property="INSURE_DES", type="string", format="string", example="AppleCare+ for iPhone 13"),
 *         @OA\Property(property="INSURE_SUM", type="string", format="string", example="5090"),
 *      ),
 *   ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * 
 * @OA\Post(
 * path="/API-Corelease/api/new_prospect_cus",
 * summary="Insert new customer",
 * description="new prospect",
 * operationId="new_prospect",
 * tags={"API QUTATATION"},
 * security={{ "Api-Token": {} }},
 *   @OA\RequestBody(
 *      required=true,
 *      description="Prospect customer information",
 *      @OA\MediaType(
 *          mediaType="multipart/form-data",
 *          @OA\Schema(
 *              @OA\Property(property="file", type="file", format="binary" ),
 *              @OA\Property(property="resume", type="string", format="string"),
 *              @OA\Property(property="TAX_ID", type="string", format="string", example="4856465441239"),
 *          ),
 *      ),
 *   ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * 
 * 
 * 
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
