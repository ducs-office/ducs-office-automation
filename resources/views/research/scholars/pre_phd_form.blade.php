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
                    <div class="w-1/2 flex flex-wrap">
                        <p class="ml-1 p-1"> Name of Research Scholar: </p>
                        <p class="ml-1 p-1 font-semibold"> {{ $scholar->name }} </p>
                    </div>
                    <div class="flex flex-wrap">
                        <p class="ml-1 p-1"> Enrolment Number: </p>
                        <p class="ml-1 p-1 font-semibold"> {{ $scholar->enrolment_id ?? '-' }} </p>
                    </div>
                </div>
                <div class="flex divide-x divide-black">
                    <div class="w-1/2 flex flex-wrap">
                        <p class="ml-1 p-1"> Email: </p>
                        <p class="ml-1 p-1 font-semibold"> {{ $scholar->email }} </p>
                    </div>
                    <div class="flex flex-wrap">
                        <p class="ml-1 p-1"> Mobile: </p>
                        <p class="ml-1 p-1 font-semibold"> {{ $scholar->phone ?? '-'}} </p>
                    </div>
                </div>
                <div class="flex divide-x divide-black">
                    <div class="w-1/2 flex flex-wrap">
                        <p class="ml-1 p-1"> Date of initial registeration: </p>
                        <p class="ml-1 p-1 font-semibold"> {{ optional($scholar->registration_date)->format('d F, Y') }}</p>
                    </div>
                    <div class="flex flex-wrap">
                        <p class="ml-1 p-1"> Period of extension (if any): </p>
                        <p class="ml-1 p-1 font-semibold"> </p>
                    </div>
                </div>
                <div class="flex flex-wrap">
                    <p class="ml-1 p-1"> Registeraion valid up to: </p>
                    <p class="ml-1 p-1 font-semibold"> {{ optional($scholar->registrationValidUpto())->format('d F, Y') }}
                </div>
                <div class="flex divide-x divide-black">
                    <div class="w-1/2">
                        <p class="ml-1 p-1"> Supervisor's details: </p>
                    </div>
                    <div>
                        <p class="ml-1 p-1"> Co-supervisor (if any): </p>
                    </div>
                </div>
                <div class="flex divide-x divide-black">
                    <div class="w-1/2 flex flex-wrap">
                        <p class="ml-1 p-1"> Name: </p>
                        <p class="ml-1 p-1 font-semibold"> {{ $scholar->currentSupervisor->name }} </p>
                    </div>
                    <div class="flex flex-wrap">
                        <p class="ml-1 p-1"> Name: </p>
                        <p class="ml-1 p-1 font-semibold"> {{ optional($scholar->currentCosupervisor)->name }} </p>
                    </div>
                </div>
                <div class="flex divide-x divide-black">
                    <div class="w-1/2 flex flex-wrap">
                        <p class="ml-1 p-1"> Email: </p>
                        <p class="ml-1 p-1 font-semibold"> {{ $scholar->currentSupervisor->email}} </p>
                    </div>
                    <div class="flex flex-wrap">
                        <p class="ml-1 p-1"> Email: </p>
                        <p class="ml-1 p-1 font-semibold"> {{ optional($scholar->currentCosupervisor)->email }} </p>
                    </div>
                </div>
                <div class="flex divide-x divide-black">
                    <div class="w-1/2 flex flex-wrap">
                        <p class="ml-1 p-1"> Mobile: </p>
                        <p class="ml-1 p-1 font-semibold"> {{ $scholar->currentSupervisor->phone }} </p>
                    </div>
                    <div class="flex flex-wrap">
                        <p class="ml-1 p-1"> Mobile: </p>
                        <p class="ml-1 p-1 font-semibold"> {{ optional($scholar->currentCosupervisor)->phone }} </p>
                    </div>
                </div>
                <div class="flex divide-x divide-black h-24">
                    <div class="w-1/2 flex flex-wrap">
                        <p class="ml-1 p-1"> Address: </p>
                        <p class="ml-1 p-1 font-semibold"> {{ $scholar->currentSupervisor->address }} </p>
                    </div>
                    <div class="flex flex-wrap">
                        <p class="ml-1 p-1"> Address: </p>
                        <p class="ml-1 p-1 font-semibold"> {{ optional($scholar->currentCosupervisor)->address }} </p>
                    </div>
                </div>
                <div class="flex flex-wrap">
                    <p class="ml-1 p-1 font-bold"> No. of Publications: </p>
                    <p class="ml-1 mr-4 p-1"> In SCI/SCIE journals
                        <span class="inline font-semibold ml-1 p-1"> {{ $scholar->CountSCIOrSCIEJournals() }} </span>
                    </p>
                    <p class="mx-4 p-1"> In Scopus but not SCI/SCIE
                        <span class="inline font-semibold ml-1 p-1"> {{ $scholar->CountScopusNotSCIOrSCIEPublications() }} </span>
                    </p>
                    <p class="mx-4 p-1"> MR
                        <span class="inline font-semibold ml-1 p-1"> {{ $scholar->countMRPublications() }} </span>
                    </p>
                    <p class="ml-4 p-1"> Total:
                        <span class="inline font-semibold ml-1 p-1"> {{ $scholar->publications()->count() }} </span>
                    </p>
                </div>
                <div class="flex flex-wrap h-20">
                    <p class="ml-1 p-1"> Proposed Title of the Thesis: </p>
                    <p class="ml-1 p-1 font-semibold"> {{ $scholar->proposed_title}} </p>
                </div>
                <div class="flex divide-x divide-black h-24">
                    <div class="w-1/2 flex items-end">
                        <p class="ml-1 p-1"> Signature of Research Scholar: </p>
                    </div>
                    <div class="flex items-end">
                        <p class="ml-1 p-1"> Signature of Supervisor(s): </p>
                    </div>
                </div>
                <div>
                    <div class="font-bold flex flex-wrap">
                        <p class="ml-1 p-1"> Title finalized at the PrePhD seminar: </p>
                        <p class="ml-1 p-1 font-semibold"> {{ optional($scholar->prePhdSeminar)->finalized_title}} </p>
                    </div>
                    <div class="flex justify-between items-end h-32">
                        <p class="ml-1 p-1"> Date: </p>
                        <p class="p-1"> Signatue of Research Scholar </p>
                        <p class="p-1"> Signature of Supervisor(s) </p>
                        <p class="p-1 mr-1"> Signature of Head </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @can('create', [App\Models\PrePhdSeminar::class, $scholar])
        <div class="flex items-end justify-center">
            <div>
                <p class="font-bold text-lg"> Are you sure you want to apply for Pre-Phd Seminar ? </p>
                <div class="flex justify-center">
                    <form action="{{ route('scholars.pre_phd_seminar.apply', $scholar) }}" method="POST">
                        @csrf_token
                        <button class="btn btn-magenta is-sm m-2" >
                            Apply
                        </button>
                    </form>
                    <a href="{{ route('scholars.profile') }}" class="btn btn-magenta is-sm m-2">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    @endcan
</body>
</html>
