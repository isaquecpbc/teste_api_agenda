<?php

namespace App\Swagger;

/**
 * @OA\Schema(
 *     schema="Schedule",
 *     type="object",
 *     title="Schedule",
 *     required={"title", "type", "description", "status", "start_at", "conclusion_at", "user_id"},
 *     properties={
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="title", type="string", example="Meeting"),
 *         @OA\Property(property="type", type="string", example="meeting"),
 *         @OA\Property(property="description", type="string", example="Team meeting"),
 *         @OA\Property(property="status", type="boolean", example=true),
 *         @OA\Property(property="start_at", type="string", format="date-time", example="2024-07-15T00:00:00Z"),
 *         @OA\Property(property="conclusion_at", type="string", format="date-time", example="2024-07-15T01:00:00Z"),
 *         @OA\Property(property="user_id", type="integer", example=1)
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="ScheduleStoreRequest",
 *     type="object",
 *     title="Schedule Store Request",
 *     required={"title", "type", "description", "status", "start_at", "conclusion_at", "user_id"},
 *     properties={
 *         @OA\Property(property="title", type="string", example="Meeting"),
 *         @OA\Property(property="type", type="string", example="meeting"),
 *         @OA\Property(property="description", type="string", example="Team meeting"),
 *         @OA\Property(property="status", type="boolean", example=true),
 *         @OA\Property(property="start_at", type="string", format="date-time", example="2024-07-15T00:00:00Z"),
 *         @OA\Property(property="conclusion_at", type="string", format="date-time", example="2024-07-15T01:00:00Z"),
 *         @OA\Property(property="user_id", type="integer", example=1)
 *     }
 * )
 */
