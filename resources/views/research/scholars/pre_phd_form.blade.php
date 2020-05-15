<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pre-PhD-Seminar</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @routes
    <style>
        html,body {
            font-size: 14px;
            font-family: 'Times New Roman', Times, serif;
        }
    </style>
</head>
<body class="bg-gray-200 overflow-y-visible leading-tight">
    <div class="flex justify-center">
        <div class="p-6 page-card m-6 w-3/5">
            <div class="m-6 flex justify-between items-center">
                <img src="{{ asset('images/university-emblem.png') }}" alt="University of Delhi - Emblem" 
                class="w-16 sm:w-32 flex-shrink-0 pb-3">
                <div class="text-right">
                    <p> संगणक विभाग </p>
                    <p class="font-bold"> DEPARTMENT OF COMPUTER SCIENCE </p>
                    <p> दिल्ली विश्वविद्यालय, दिल्ली – 110 007 (भारत) </p>
                    <p class="font-bold"> UNIVERSITY OF DELHI, DELHI – 110 007 (INDIA) </p>
                    <p class="font-bold"> http:// cs.du.ac.in/ </p>
                </div>
            </div>
            <h1 class="text-center text-lg underline font-bold"> Pre-Ph.D. Seminar</h1>
            <div class="divide-y divide-black border-black border-solid border m-6"> 
                <div class="flex divide-x divide-black h-12">
                    <div class="p-1 w-1/2"> Name of Research Scholar: </div>    
                    <div class="p-1"> Enrolment Number: </div>    
                </div>
                <div class="flex divide-x divide-black">
                    <div class="p-1 w-1/2"> Email: </div>    
                    <div class="p-1"> Mobile: </div>    
                </div>
                <div class="flex divide-x divide-black">
                    <div class="p-1 w-1/2"> Date of initial registeration: </div>    
                    <div class="p-1"> Period of extension (if any): </div>    
                </div>
                <div class="p-1">
                    Registeraion valid up to:   
                </div>
                <div class="flex divide-x divide-black">
                    <div class="p-1 w-1/2"> Supervisor's details: </div>    
                    <div class="p-1"> Co-supervisor (if any): </div>    
                </div>
                <div class="flex divide-x divide-black">
                    <div class="p-1 w-1/2"> Name: </div>    
                    <div class="p-1"> Name: </div>    
                </div>
                <div class="flex divide-x divide-black">
                    <div class="p-1 w-1/2"> Email: </div>    
                    <div class="p-1"> Email: </div>    
                </div>
                <div class="flex divide-x divide-black">
                    <div class="p-1 w-1/2"> Mobile: </div>    
                    <div class="p-1"> Mobile: </div>    
                </div>
                <div class="flex divide-x divide-black h-24">
                    <div class="p-1 w-1/2"> Address: </div>    
                    <div class="p-1"> Address: </div>    
                </div>
                <div class="p-1 font-bold">
                    No. of Publications:  
                </div>
                <div class="p-1 flex divide-x divide-black h-20">
                    Proposed Title of the Thesis:
                </div>
                <div class="flex divide-x divide-black h-24">
                    <div class="p-1 w-1/2 flex items-end"> Signature of Research Scholar: </div>    
                    <div class="p-1 flex items-end"> Signature of Supervisor(s): </div>    
                </div>
                <div>
                    <div class="p-1 font-bold">
                        Title finalized at the PrePhD seminar:   
                    </div>
                    <div class="flex justify-between items-end h-32">
                        <p class="p-1"> Date: </p>
                        <p class="p-1"> Signatue of Research Scholar </p>
                        <p class="p-1"> Signature of Supervisor(s) </p>
                        <p class="p-1"> Signature of Head </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @can('createPhDSeminar', App\Models\ScholarAppeal::class)
        <div class="flex items-end justify-center"> 
            <div>
                <p class="font-bold text-lg"> Are you sure you want to apply for Pre-Phd Seminar ? </p>
                <div class="flex justify-center"> 
                    <a href="{{ route('scholars.profile') }}" class="btn btn-magenta is-sm m-2">
                        Cancel  
                    </a>
                    <form action="{{ route('scholars.pre_phd_seminar.apply', $scholar) }}" method="POST">
                        @csrf_token
                        <button class="btn btn-magenta is-sm m-2" > 
                            Apply
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endcan
</body>
</html>