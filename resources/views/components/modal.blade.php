@props(['formAction' => false])

<div>
    @if ($formAction)
        <form wire:submit.prevent="{{ $formAction }}">
    @endif

    <div class=" row p-3 sm:px-3 sm:py-3 w-100">
        <div class="col-11">
            @if (isset($title))
                {{ $title }}
            @endif
        </div>
        <div class="col">
            <div class="row">
                @if (isset($tools))
                    <div class="col">
                        {{ $tools }}
                    </div>
                @endif
                <div class="col">
                    <button wire:click="$dispatch('closeModal')" class="btn btn-icon"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-x">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M18 6l-12 12" />
                            <path d="M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-sm-12 col-lg-12">

            <div class=" px-1 sm:p-1">

                {{ $content }}

            </div>

        </div>

        <div class="col-12 col-sm-5 col-lg-5">
            <div class="bg-gray px-1 pb-1 sm:px-1 m-3">
                {{ $buttons }}
            </div>
        </div>
    </div>
    @if ($formAction)
        </form>
    @endif
</div>
