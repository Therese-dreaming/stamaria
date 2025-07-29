@extends('layouts.admin')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mt-4">Settings</h1>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">System Settings</h5>
                <form>
                    <div class="mb-3">
                        <label for="site_name" class="form-label">Site Name</label>
                        <input type="text" class="form-control" id="site_name" value="Santa Marta Parish">
                    </div>
                    <div class="mb-3">
                        <label for="contact_email" class="form-label">Contact Email</label>
                        <input type="email" class="form-control" id="contact_email" value="diocesansaintmartha@gmail.com">
                    </div>
                    <div class="mb-3">
                        <label for="contact_phone" class="form-label">Contact Phone</label>
                        <input type="text" class="form-control" id="contact_phone" value="0917-366-4359">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" rows="3">B. Morcilla St., Pateros, Metro Manila</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </form>
            </div>
        </div>
    </div>
@endsection
