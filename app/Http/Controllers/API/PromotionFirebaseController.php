<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\PromotionFirebaseServiceInterface;
use Illuminate\Http\Request;

class PromotionFirebaseController extends Controller
{
    protected $promotionService;

    public function __construct(PromotionFirebaseServiceInterface $promotionService)
    {
        $this->promotionService = $promotionService;
    }

    public function create(Request $request)
    {
        try {
            $promotion = $this->promotionService->createPromotion($request->all());
            return response()->json($promotion, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $promotion = $this->promotionService->updatePromotion($id, $request->all());
            return response()->json($promotion);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function updateReferentiels(Request $request, $id)
    {
        try {
            $promotion = $this->promotionService->updatePromotionReferentiels($id, $request->referentiels);
            return response()->json($promotion);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function updateEtat(Request $request, $id)
    {
        try {
            $promotion = $this->promotionService->updatePromotionEtat($id, $request->etat);
            return response()->json($promotion);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function index()
    {
        $promotions = $this->promotionService->getAllPromotions();
        return response()->json($promotions);
    }

    public function encours()
    {
        $promotion = $this->promotionService->getPromotionEncours();
        return response()->json($promotion);
    }

    public function cloturer($id)
    {
        try {
            $result = $this->promotionService->cloturerPromotion($id);
            return response()->json(['message' => 'Promotion clôturée avec succès']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getReferentiels($id)
    {
        $referentiels = $this->promotionService->getPromotionReferentiels($id);
        return response()->json($referentiels);
    }

    public function getStats($id)
    {
        $stats = $this->promotionService->getPromotionStats($id);
        return response()->json($stats);
    }
    
}