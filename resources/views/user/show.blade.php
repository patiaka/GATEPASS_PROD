<x-app-layout>
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="user-profile-header-banner">
                    <img src="{{ asset('assets/img/pages/profile-banner.png') }}" alt="Banner image"
                        class="rounded-top" />
                </div>
                <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                    <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                        <img src="{{ asset('assets/img/avatars/h.svg') }}" alt="Avatar"
                            class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img ">
                    </div>
                    <div class="flex-grow-1 mt-3 mt-sm-5">
                        <div
                            class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                            <div class="user-profile-info">
                                <h4>{{ $user->name }}</h4>
                                <ul
                                    class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                    <li class="list-inline-item fw-medium"><i class="bx bx-star"></i> {{ $user->role }}
                                    </li>
                                    <li class="list-inline-item fw-medium"><i class="bx bx-envelope"></i>
                                        {{ $user->email }}
                                    </li>
                                    <li class="list-inline-item fw-medium"><i class="bx bx-buildings"></i>
                                        Department: {{ $user->department->name }}
                                    </li>
                                    <li class="list-inline-item fw-medium"><i class="bx bx-building-house"></i>
                                        compagny: {{ $user->compagnie->name }}
                                    </li>
                                </ul>
                            </div>

                            <a href="javascript:void(0)" @class(['btn text-nowrap text-white' ,'bg-primary'=>
                                $user->status == 1,
                                'bg-danger' => $user->status == 0,
                                ])>
                                <i class="bx bx-user-check me-1"></i> {{ $user->status ? 'Active':'Inactive' }}
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Header -->

    <!-- User Profile Content -->
    <div class="row">
        <div class="col-xl-4 col-lg-5 col-md-5">
            <!-- About User -->
            <div class="card mb-4">
                <div class="card-body">
                    <small class="text-muted text-uppercase">Info general</small>
                    <form action="{{ route('user.update',$user) }}" method="post">
                        @csrf
                        @method('PATCH')
                        <ul class="list-unstyled mb-4 mt-3">
                            <li class="d-flex align-items-center mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" name="email" value="{{ $user->email }}"
                                        placeholder="Votre email">
                                </div>
                            </li>
                        </ul>
                        <ul class="list-unstyled mb-4 mt-3">

                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary me-2">Valider</button>
                            </div>
                        </ul>
                    </form>
                </div>
            </div>
            <!--/ About User -->
        </div>
    </div>
    <!--/ User Profile Content -->
</x-app-layout>