@php
    /** @var \App\Models\PatientHistory $record */
    $diseases = $record->diseases->pluck('Name')->filter()->values();
    $symptoms = $record->symptoms->pluck('Name')->filter()->values();
    $prescriptions = $record->prescriptions;
@endphp

<div class="space-y-3 py-1">
    <div class="grid gap-3 md:grid-cols-2">
        <div>
            <p class="text-xs font-medium text-gray-500">Complain Of</p>
            <p class="text-sm text-gray-900 dark:text-gray-100">{{ $record->patient?->complain_of ?: '-' }}</p>
        </div>
        <div>
            <p class="text-xs font-medium text-gray-500">Next Appointment</p>
            <p class="text-sm text-gray-900 dark:text-gray-100">
                {{ $record->NextAppointmentDate?->format('d M Y h:i A') ?: '-' }}
            </p>
        </div>
    </div>

    <div class="grid gap-3 md:grid-cols-2">
        <div>
            <p class="text-xs font-medium text-gray-500">Diseases</p>
            <p class="text-sm text-gray-900 dark:text-gray-100">{{ $diseases->isNotEmpty() ? $diseases->implode(', ') : '-' }}</p>
        </div>
        <div>
            <p class="text-xs font-medium text-gray-500">Symptoms</p>
            <p class="text-sm text-gray-900 dark:text-gray-100">{{ $symptoms->isNotEmpty() ? $symptoms->implode(', ') : '-' }}</p>
        </div>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-white/10">
        <table class="min-w-full text-left text-xs">
            <thead class="bg-gray-50 dark:bg-white/5">
                <tr>
                    <th class="px-2 py-1.5 font-semibold">Medicine</th>
                    <th class="px-2 py-1.5 font-semibold">Dose</th>
                    <th class="px-2 py-1.5 font-semibold">Time</th>
                    <th class="px-2 py-1.5 font-semibold">Quantity</th>
                    <th class="px-2 py-1.5 font-semibold">Anupana</th>
                    <th class="px-2 py-1.5 font-semibold">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                @forelse ($prescriptions as $prescription)
                    <tr>
                        <td class="px-2 py-1.5">
                            {{ $prescription->medicine?->Name ?: '-' }}
                            @if ($prescription->MedicineFormName)
                                <span class="text-gray-500">({{ $prescription->MedicineFormName }})</span>
                            @endif
                        </td>
                        <td class="px-2 py-1.5">{{ $prescription->Dose ?: '-' }}</td>
                        <td class="px-2 py-1.5">{{ $prescription->TimeOfAdministration ?: '-' }}</td>
                        <td class="px-2 py-1.5">{{ $prescription->Duration ?: '-' }}</td>
                        <td class="px-2 py-1.5">{{ $prescription->Anupana ?: '-' }}</td>
                        <td class="px-2 py-1.5">{{ ($prescription->Amount !== null && $prescription->Amount !== '') ? $prescription->Amount : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-2 py-2 text-gray-500">No medicines added.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="grid gap-3 md:grid-cols-2">
        <div>
            <p class="text-xs font-medium text-gray-500">Remark</p>
            <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $record->Remark ?: '-' }}</p>
        </div>
        <div>
            <p class="text-xs font-medium text-gray-500">Note</p>
            <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $record->Note ?: '-' }}</p>
        </div>
    </div>
</div>
