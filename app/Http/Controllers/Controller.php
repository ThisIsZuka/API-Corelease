<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="L5 OpenApi",
 *      description="L5 Swagger OpenApi description"
 * )
 *
 */

/**
 * @OA\Get(
 * path="/API-Corelease/api/AllMaster",
 * summary="Master Data All",
 * description="ข้อมูล Master ทั้งหมด",
 * operationId="AllMaster",
 * tags={"API Master"},
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="Sorry, wrong Data. Please try again")
 *        )
 *     )
 * ),
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
 *       @OA\Property(property="message", type="string", example="Sorry, wrong Data. Please try again")
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
 *       @OA\Property(property="message", type="string", example="Sorry, wrong Data. Please try again")
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
 *       @OA\Property(property="message", type="string", example="Sorry, wrong Data. Please try again")
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
 *       @OA\Property(property="message", type="string", example="Sorry, wrong Data. Please try again")
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
 *       @OA\Property(property="message", type="string", example="Sorry, wrong Data. Please try again")
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
 *       @OA\Property(property="message", type="string", example="Sorry, wrong Data. Please try again")
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
 *       @OA\Property(property="message", type="string", example="Sorry, wrong Data. Please try again")
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
 *       @OA\Property(property="message", type="string", example="Sorry, wrong Data. Please try again")
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
 *       @OA\Property(property="message", type="string", example="Sorry, wrong Data. Please try again")
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
 *       @OA\Property(property="message", type="string", example="Sorry, wrong Data. Please try again")
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
 *       @OA\Property(property="message", type="string", example="Sorry, wrong Data. Please try again")
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
 *       @OA\Property(property="message", type="string", example="Sorry, wrong Data. Please try again")
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
 *       @OA\Property(property="message", type="string", example="Sorry, wrong Data. Please try again")
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
 *       @OA\Property(property="message", type="string", example="Sorry, wrong Data. Please try again")
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
 *       @OA\Property(property="message", type="string", example="Sorry, wrong Data. Please try again")
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
 *       @OA\Property(property="message", type="string", example="Sorry, wrong Data. Please try again")
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
 *       @OA\Property(property="message", type="string", example="Sorry, wrong Data. Please try again")
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
 *       @OA\Property(property="message", type="string", example="Sorry, wrong Data. Please try again")
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
 *       @OA\Property(property="message", type="string", example="Sorry, wrong Data. Please try again")
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
 *       @OA\Property(property="message", type="string", example="Sorry, wrong Data. Please try again")
 *        )
 *     )
 * ),
 * 
 * 
 * @OA\Get(
 * path="/API-Corelease/api/master_university/{PROVINCE_ID}",
 * summary="Master Data มหาวิทยาลัย",
 * description="มหาวิทยาลัย",
 * operationId="master_university",
 * tags={"API University"},
 * @OA\Parameter(
 *     name="PROVINCE_ID",
 *     in="path",
 *     description="รหัสจังหวัด เช่น 10",
 *     required=true,
 * ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="Sorry, wrong Data. Please try again")
 *        )
 *     )
 * ),
 * 
 * @OA\Post(
 * path="/API-Corelease/api/master_university",
 * summary="Master Data มหาวิทยาลัย",
 * description="มหาวิทยาลัย",
 * operationId="Post-master_university",
 * tags={"API University"},
 * @OA\RequestBody(
 *    required=false,
 *    description="PROVINCE_ID, DISTRICT_ID",
 *    @OA\JsonContent(
 *       required={"PROVINCE_ID","DISTRICT_ID"},
 *       @OA\Property(property="PROVINCE_ID", type="integer", example=10),
 *       @OA\Property(property="DISTRICT_ID", type="integer", example=null),
 *    ),
 * ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="Sorry, wrong Data. Please try again")
 *        )
 *     )
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
