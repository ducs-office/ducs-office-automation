@extends('layouts.teachers')
@section('body')
    <div class="container mx-auto p-4 bg-white h-full rounded shadow-md">
        <div class="flex items-center mb-4">
            <img src="https://placehold.it/150/150" class="w-24 h-24 mr-4">
            <div>
                <h3 class="text-2xl font-bold">{{ $teacher->name }}</h3>
                <h5 class="text-xl text-gray-700 font-medium">Professor</h5>
            </div>
        </div>
        <p>
            <b>College:</b> Acharya Narendra Dev College
        </p>
        <p>
            <b>Email:</b> <a href="mailto:{{ $teacher->email }}">{{ $teacher->email }}</a>
        </p>
        <p>
            <b>Phone:</b> <a href="tel:9876543210">9876543210</a>
        </p>
        <address>
            142/B, Street Name, Area, City - Pincode
        </address>

        <h5 class="mt-12 mb-4 text-xl pb-1 border-b font-medium">Bank Details</h5>
        <p>
            <b>Bank:</b> State Bank of India
        </p>
        <p>
            <b>Account Number:</b> 32747044805
        </p>
        <p>
            <b>Branch:</b> Nawab Gate, Rampur
        </p>
        <p>
            <b>IFS Code:</b> SBIN0000702
        </p>


        <h5 class="mt-12 mb-4 text-xl pb-1 border-b font-medium">Teaching Record</h5>

        <ul>
            <li class="mb-4">
                <h6 class="font-semibold mb-2">July 2019 - Acharya Narendra Dev College</h6>
                <ul>
                    <li>B.Sc. (H) Computer Science - Database Management System</li>
                    <li>B.Sc. (Prog) Computer Science - Database Management System</li>
                </ul>
            </li>
            <li class="mb-4">
                <h6 class="font-semibold mb-2">Jan 2020 - Acharya Narendra Dev College</h6>
                <ul>
                    <li>B.Sc. (H) Computer Science - Data Mining</li>
                </ul>
            </li>
        </ul>
    </div>
@endsection
