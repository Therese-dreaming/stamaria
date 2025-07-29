@extends('layouts.main')

@section('title', 'Home - Sta. Marta Parish')

@section('content')
    <!-- Hero Section -->
    <div class="relative min-h-screen">
        <img src="{{ asset('images/church-bg.jpg') }}" alt="Church Background" class="absolute inset-0 w-full h-full object-cover brightness-50" />
        <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-4">
            <h1 class="text-6xl md:text-7xl font-bold mb-4 tracking-wider">
                DAMBANANG PANGDIYOSESIS<br />NI STA. MARTA
            </h1>
            <h2 class="font-['Great_Vibes'] text-5xl md:text-6xl">Parokya ni San Roque</h2>
        </div>
    </div>

    <!-- Service Hours Section -->
    <div class="bg-white py-20">
        <h2 class="text-[#0d5c2f] text-5xl font-bold text-center mb-16">SERVICE HOURS</h2>

        <div class="container mx-auto px-4 grid md:grid-cols-2 gap-12">
            <!-- Mass Schedule -->
            <div class="text-center">
                <div class="mb-12">
                    <h3 class="font-['Great_Vibes'] text-4xl text-[#0d5c2f] mb-2">Daily</h3>
                    <h4 class="text-2xl font-bold text-[#0d5c2f] mb-4">MASS SCHEDULE</h4>
                    <p class="text-[#b8860b]">MON - SAT 6:00AM & 6:00PM</p>
                </div>

                <div class="mb-12">
                    <h3 class="font-['Great_Vibes'] text-4xl text-[#0d5c2f] mb-2">Anticipated</h3>
                    <h4 class="text-2xl font-bold text-[#0d5c2f] mb-4">MASS SCHEDULE</h4>
                    <p class="text-[#b8860b]">SAT 6:00PM</p>
                </div>

                <div>
                    <h3 class="font-['Great_Vibes'] text-4xl text-[#0d5c2f] mb-2">Sunday</h3>
                    <h4 class="text-2xl font-bold text-[#0d5c2f] mb-4">MASS SCHEDULE</h4>
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <h5 class="text-[#b8860b] font-bold mb-2">MORNING</h5>
                            <div class="space-y-1">
                                <p>5:00 AM</p>
                                <p>6:15AM</p>
                                <p>7:30 AM</p>
                                <p>8:45 AM</p>
                                <p>10:00AM</p>
                            </div>
                        </div>
                        <div>
                            <h5 class="text-[#b8860b] font-bold mb-2">AFTERNOON</h5>
                            <div class="space-y-1">
                                <p>3:00 PM</p>
                                <p>4:00 PM</p>
                                <p>5:15 PM</p>
                                <p>6:30 PM</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Office Transaction -->
            <div class="text-center border-l border-[#0d5c2f]">
                <h3 class="font-['Great_Vibes'] text-4xl text-[#0d5c2f] mb-2">Office</h3>
                <h4 class="text-4xl font-bold text-[#0d5c2f] mb-12">TRANSACTION</h4>

                <div class="mb-8">
                    <h5 class="text-[#b8860b] text-2xl font-bold mb-4">MONDAY & HOLIDAYS</h5>
                    <p class="text-3xl font-bold text-[#0d5c2f]">CLOSED</p>
                </div>

                <div class="mb-8">
                    <h5 class="text-[#b8860b] text-2xl font-bold mb-4">TUESDAY - SATURDAY</h5>
                    <p>8:00 AM - 12:00 NN</p>
                    <p>1:00 PM - 5:00 PM</p>
                </div>

                <div>
                    <h5 class="text-[#b8860b] text-2xl font-bold mb-4">SUNDAY</h5>
                    <p>8:00 AM - 12:00 NN</p>
                    <p>3:00 PM - 5:00 PM</p>
                </div>
            </div>
        </div>

        <!-- Ministry Cards Section (Now full-width and moved outside container) -->
        <div class="w-full bg-white py-20">
            <div class="flex flex-col md:flex-row w-full h-[80vh]">
                <!-- Sacraments Card -->
                <div class="relative w-full md:w-1/3 h-full">
                    <img src="{{ asset('images/sacraments.jpg') }}" alt="Sacraments" class="w-full h-full object-cover grayscale">
                    <div class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center">
                        <h3 class="text-white text-5xl font-bold mb-4 font-['Rowdies']">SACRAMENTS</h3>
                        <a href="" class="text-[#0d5c2f] font-bold bg-white px-6 py-1.5 text-sm rounded-xl">LEARN
                            MORE</a>
                    </div>
                </div>

                <!-- Devotion Card -->
                <div class="relative w-full md:w-1/3 h-full">
                    <img src="{{ asset('images/devotion.jpg') }}" alt="Devotion" class="w-full h-full object-cover grayscale">
                    <div class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center">
                        <h3 class="text-white text-5xl font-bold mb-4 font-['Rowdies']">DEVOTION</h3>
                        <a href="" class="text-[#0d5c2f] font-bold bg-white px-6 py-1.5 text-sm rounded-xl">LEARN
                            MORE</a>
                    </div>
                </div>

                <!-- Ministries Card -->
                <div class="relative w-full md:w-1/3 h-full">
                    <img src="{{ asset('images/ministries.jpg') }}" alt="Ministries" class="w-full h-full object-cover grayscale">
                    <div class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center">
                        <h3 class="text-white text-5xl font-bold mb-4 font-['Rowdies']">MINISTRIES</h3>
                        <a href="" class="text-[#0d5c2f] font-bold bg-white px-6 py-1.5 text-sm rounded-xl">LEARN
                            MORE</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Simbahan Section (unchanged) -->
        <div class="bg-white py-24">
            <div class="container mx-auto px-4 flex flex-col md:flex-row items-center gap-16">
                <div class="md:w-1/2">
                    <h2 class="text-[#0d5c2f] text-6xl font-bold mb-12 font-['Rowdies'] leading-tight">
                        ANG SIMBAHAN<br>NG PATEROS
                    </h2>
                    <div class="space-y-6">
                        <div class="group">
                            <a href="" class="bg-white shadow-lg hover:shadow-xl w-full py-4 px-8 text-2xl flex items-center justify-between rounded-lg border border-gray-100 hover:border-[#0d5c2f] transition-all duration-300">
                                <span class="font-['Rowdies'] text-gray-700">ANG PAROKYA</span>
                                <span class="bg-[#0d5c2f] text-white w-8 h-8 flex items-center justify-center rounded-full group-hover:bg-[#b8860b] transition-colors text-lg leading-none">›</span>
                            </a>
                        </div>
                        <div class="group">
                            <a href="" class="bg-white shadow-lg hover:shadow-xl w-full py-4 px-8 text-2xl flex items-center justify-between rounded-lg border border-gray-100 hover:border-[#0d5c2f] transition-all duration-300">
                                <span class="font-['Rowdies'] text-gray-700">ANG DIYOSESIS</span>
                                <span class="bg-[#0d5c2f] text-white w-8 h-8 flex items-center justify-center rounded-full group-hover:bg-[#b8860b] transition-colors text-lg leading-none">›</span>
                            </a>
                        </div>
                        <div class="group">
                            <a href="" class="bg-white shadow-lg hover:shadow-xl w-full py-4 px-8 text-2xl flex items-center justify-between rounded-lg border border-gray-100 hover:border-[#0d5c2f] transition-all duration-300">
                                <span class="font-['Rowdies'] text-gray-700">ANG KAPARIAN</span>
                                <span class="bg-[#0d5c2f] text-white w-8 h-8 flex items-center justify-center rounded-full group-hover:bg-[#b8860b] transition-colors text-lg leading-none">›</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="md:w-1/2">
                    <div class="relative">
                        <img src="{{ asset('images/altar.jpg') }}" alt="Church Altar" class="w-full h-auto rounded-2xl shadow-2xl">
                        <div class="absolute inset-0 rounded-2xl shadow-inner"></div>
                    </div>
                </div>
            </div>
        </div>
@endsection
