<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\TourComment;
use App\Models\TourImage;
use App\Repositories\TourRepository;
use App\Support\Enum\CommentStatus;
use App\Support\Enum\DestinationType;
use Illuminate\Http\Request;

class ToursController extends Controller
{

    public function __construct(protected TourRepository $tourRepository)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('tours/list', ['tours' => $this->tourRepository->getActiveToursQuery()->get()]);
    }


    public function home()
    {
        $tours = $this->tourRepository->getDestinationTypes(DestinationType::Home);
        return view('tours/list', ['tours' => $tours, 'destination_type' => DestinationType::Home->value]);
    }


    public function abroad()
    {
        $tours = $this->tourRepository->getDestinationTypes(DestinationType::Abroad);
        return view('tours/list', ['tours' => $tours, 'destination_type' => DestinationType::Abroad->value]);
    }


    public function search(Request $request)
    {
        $tours = $this->tourRepository->search($request->all());
        return view('tours/list', [
            'tours' => $tours,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $tour = $this->tourRepository->getTour($slug);
        $tourComments = TourComment::where('tour_id', $tour->id)
            ->where('status', CommentStatus::Published)
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('tours/detail', ['tour' => $tour, 'tourComments' => $tourComments]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
