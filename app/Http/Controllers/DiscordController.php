<?php

namespace App\Http\Controllers;

use App\Discord;
use Illuminate\Http\Request;
use App\Notifications\wtnotif;

class DiscordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $discord = Discord::find(1);
        $discord->notify(new wtnotif());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Discord  $discord
     * @return \Illuminate\Http\Response
     */
    public function show(Discord $discord)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Discord  $discord
     * @return \Illuminate\Http\Response
     */
    public function edit(Discord $discord)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Discord  $discord
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Discord $discord)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Discord  $discord
     * @return \Illuminate\Http\Response
     */
    public function destroy(Discord $discord)
    {
        //
    }
}
