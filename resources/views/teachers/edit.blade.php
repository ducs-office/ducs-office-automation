@extends('layouts.teachers')
@section('body')
    <div class="container mx-auto p-4 bg-white h-full rounded shadow-md">
        <form action="" method="POST">
            @csrf_token @method('PATCH')
            <div class="flex items-center mb-4">
                <label for="profile_photo" class="cursor-pointer relative mr-4">
                    <input type="file" name="photo" id="profile_photo" class="hidden">
                    <img src="https://placehold.it/150/150" class="w-24 h-24">
                    <div class="absolute inset-0 bg-black-30 text-white flex items-center justify-center">
                        <span class="text-xs">Click to Upload</span>
                    </div>
                </label>
                <div>
                    <input class="block form-input text-xl font-bold mb-2" value="{{ $teacher->name }}">
                    <select class="block form-input font-bold mb-2">
                        <option value="Professor">Professor</option>
                        <option value="Professor">Assistant Professor</option>
                        <option value="Professor">Ad-hoc</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center mb-2">
                <label class="w-24 form-label mr-2">College:</label>
                <input type="text" name="college" value="Acharya Narendra Dev College" class="form-input">
            </div>
            <div class="flex items-center mb-2">
                <label class="w-24 form-label mr-2">Email:</label>
                <input type="email" name="email" value="{{ $teacher->email}}" class="form-input">
            </div>
            <div class="flex items-center mb-2">
                <label class="w-24 form-label mr-2">Phone:</label>
                <input type="text" name="phone" value="9876543210" class="form-input">
            </div>
            <div class="flex items-start mb-2">
                <label class="w-24 form-label mr-2">Address:</label>
                <textarea name="address" class="form-input">142/B, Street Name, Area, City - Pincode</textarea>
            </div>

            <h5 class="mt-12 mb-4 text-xl pb-1 border-b font-medium">Bank Details</h5>
            <div class="flex items-center mb-2">
                <label class="w-24 form-label mr-2">Bank:</label>
                <input type="text" name="bank_name" value="State Bank of India" class="form-input">
            </div>
            <div class="flex items-center mb-2">
                <label class="w-24 form-label mr-2">Account Number:</label>
                <input type="text" name="bank_account_number" value="32747033108" class="form-input">
            </div>
            <div class="flex items-center mb-2">
                <label class="w-24 form-label mr-2">Branch Name:</label>
                <input type="text" name="bank_branch_name" value="Nawab Gate, Rampur" class="form-input">
            </div>
            <div class="flex items-center mb-2">
                <label class="w-24 form-label mr-2">IFS Code:</label>
                <input type="text" name="bank_ifsc" value="SBIN0000702" class="form-input">
            </div>


            <h5 class="mt-12 mb-4 text-xl pb-1 border-b font-medium">Teaching Record for Jan 2020</h5>
            <div class="mb-4">
                <ul>
                    <li>B.Sc. (H) Computer Science - Database Management System</li>
                    <li>B.Sc. (Prog) Computer Science - Database Management System</li>
                </ul>
            </div>

            <div>
                <button type="submit" class="btn btn-magenta">Update</button>
            </div>
        </form>
    </div>
@endsection
