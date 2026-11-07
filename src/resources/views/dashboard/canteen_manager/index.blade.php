<form action="{{ route('logout') }}" method="POST">
                        @csrf

                        <button type="submit"
                                class="dropdown-item text-danger">
                            <i class="fa-solid fa-right-from-bracket me-2"></i>
                            Keluar
                        </button>
                    </form>