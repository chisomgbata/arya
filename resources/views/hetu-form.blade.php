<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">

    <div
        x-data="{
            responses: $wire.$entangle('{{ $getStatePath() }}'),

            init() {
                if (!this.responses) this.responses = {};
            },

            // Safe getter
            get(key) {
                return this.responses && this.responses[key] ? this.responses[key] : null;
            },

            // Calculation Logic
            get totals() {
                let stats = { vat: 0, pit: 0, kuf: 0, hit: 0, ahit: 0 };
                for(let i=1; i<=35; i++) {
                    if(this.get('q'+i+'_vat')) stats.vat++;
                    if(this.get('q'+i+'_pit')) stats.pit++;
                    if(this.get('q'+i+'_kuf')) stats.kuf++;

                    let status = this.get('q'+i+'_status');
                    if(status === 'HitKar') stats.hit++;
                    if(status === 'AhitKar') stats.ahit++;
                }
                return stats;
            }
        }"
        class="flex flex-col "
    >

        {{-- LOOP THROUGH QUESTIONS --}}
        @foreach(range(1, 35) as $i)
            <div
                class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden">
                <div class="flex flex-col md:flex-row">

                    {{-- LEFT COLUMN: Questionnaire --}}
                    <div class="flex-1 p-6 gap-y-4 flex flex-col">

                        {{-- Q1 --}}
                        @if($i == 1)
                            <x-filament::input.wrapper class="w-full">
                                <x-slot name="label">1. તમે સવારે કેટલા વાગ્યે ઉઠો છો ?</x-slot>
                                <x-filament::input type="time" x-model="responses.Question1_Time"/>
                            </x-filament::input.wrapper>

                            {{-- Q2 --}}
                        @elseif($i == 2)
                            <div class="space-y-3">
                                <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    2. સવારે કસરત કરો છો ?
                                </label>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2">
                                        <x-filament::input.radio value="yes"
                                                                 x-model="responses.Question2_ExcerciseYestNo"/>
                                        <span class="text-sm text-gray-950 dark:text-white">Yes</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <x-filament::input.radio value="no"
                                                                 x-model="responses.Question2_ExcerciseYestNo"/>
                                        <span class="text-sm text-gray-950 dark:text-white">No</span>
                                    </label>
                                </div>
                                <div x-show="responses.Question2_ExcerciseYestNo == 'yes'" x-transition class="pt-2">
                                    <x-filament::input.wrapper>
                                        <x-filament::input.select x-model="responses.Question2_ExcerciseNames">
                                            <option value="">Select Exercise</option>
                                            <option value="Walking">Walking</option>
                                            <option value="Yoga">Yoga</option>
                                            <option value="Gym">Gym</option>
                                            <option value="Running">Running</option>
                                        </x-filament::input.select>
                                    </x-filament::input.wrapper>
                                </div>
                            </div>

                            {{-- Q3 --}}
                        @elseif($i == 3)
                            <div class="space-y-3">
                                <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    3. તમારે વ્યાશન છે ? શેનું ?
                                </label>
                                <x-filament::section default="2" class="gap-3">
                                    @foreach(['Tobaco'=>'તમાકુ', 'Masalo'=>'મસાલો/માવો', 'Cigrate'=>'બીડી/સિગરેટ', 'Alcohol'=>'દારૂ'] as $key => $lbl)
                                        <label class="flex items-center gap-3">
                                            <x-filament::input.checkbox x-model="responses.Question3_{{ $key }}"/>
                                            <span class="text-sm text-gray-950 dark:text-white">{{ $lbl }}</span>
                                        </label>
                                    @endforeach
                                </x-filament::section>
                                <x-filament::input.wrapper>
                                    <x-filament::input type="text" placeholder="Other"
                                                       x-model="responses.Question3_Other"/>
                                </x-filament::input.wrapper>
                            </div>

                            {{-- Q4 --}}
                        @elseif($i == 4)
                            <div class="space-y-3">
                                <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    4. સવારે ઉઠીને નરણા તમાકુ કે બીડી પીવો છો ?
                                </label>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2">
                                        <x-filament::input.radio value="Yes"
                                                                 x-model="responses.Question4_TobacoMorningYesNo"/>
                                        <span class="text-sm">Yes</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <x-filament::input.radio value="No"
                                                                 x-model="responses.Question4_TobacoMorningYesNo"/>
                                        <span class="text-sm">No</span>
                                    </label>
                                </div>
                            </div>

                            {{-- Q5 --}}
                        @elseif($i == 5)
                            <div class="space-y-3">
                                <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    5. સવારે ઉઠીને નરણા પાણી પીવો છો ?
                                </label>
                                <div default="1" md="3" class="gap-4">
                                    <div>
                                        <span class="text-xs text-gray-500 mb-1 block">Drink Water?</span>
                                        <div class="flex gap-3">
                                            <label class="flex items-center gap-2">
                                                <x-filament::input.radio value="Yes"
                                                                         x-model="responses.Question5_WaterMorningYesNo"/>
                                                <span class="text-sm">Yes</span>
                                            </label>
                                            <label class="flex items-center gap-2">
                                                <x-filament::input.radio value="No"
                                                                         x-model="responses.Question5_WaterMorningYesNo"/>
                                                <span class="text-sm">No</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-xs text-gray-500 mb-1 block">Type</span>
                                        <div class="flex gap-3">
                                            <label class="flex items-center gap-2">
                                                <x-filament::input.radio value="Cold"
                                                                         x-model="responses.Question5_WaterMorningType"/>
                                                <span class="text-sm">ઠંડુ</span>
                                            </label>
                                            <label class="flex items-center gap-2">
                                                <x-filament::input.radio value="Hot"
                                                                         x-model="responses.Question5_WaterMorningType"/>
                                                <span class="text-sm">ગરમ</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div>
                                        <x-filament::input.wrapper>
                                            <x-filament::input.select x-model="responses.Question5_WaterQuantities">
                                                <option value="">Select Quantity</option>
                                                <option value="1 Glass">1 Glass</option>
                                                <option value="2 Glasses">2 Glasses</option>
                                                <option value="3 Glasses">3 Glasses</option>
                                            </x-filament::input.select>
                                        </x-filament::input.wrapper>
                                    </div>
                                </div>
                            </div>

                            {{-- Q6 --}}
                        @elseif($i == 6)
                            <div class="space-y-3">
                                <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    6. સંડાસ જવા ક્યારે જવું પડે છે ?
                                </label>
                                <div default="2" class="gap-3">
                                    @foreach(['Wakeup'=>'ઉઠીને', 'AfterWater'=>'પાણી પિએ પછી', 'AfterBreakFast'=>'નાસ્તા કર્યા પછી', 'AfterIrregular'=>'અનિયમિત', 'AfterMedicine'=>'કબજિયાત ની દવા', 'AfterTabaco'=>'તમાકુ પછી'] as $val => $lbl)
                                        <label class="flex items-center gap-2">
                                            <x-filament::input.radio value="{{ $val }}"
                                                                     x-model="responses.Question6_LatrineTime"/>
                                            <span
                                                class="text-sm text-gray-700 dark:text-gray-300">{{ $lbl }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Q7 --}}
                        @elseif($i == 7)
                            <div class="space-y-3">
                                <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    7. ન્હવા ક્યારે જાવ છો ?
                                </label>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2">
                                        <x-filament::input.radio value="BeforeBreakFast"
                                                                 x-model="responses.Question7_BathBeforeOrAfterBreakFast"/>
                                        <span class="text-sm">નાસ્તા પહેલા</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <x-filament::input.radio value="AfterBreakFast"
                                                                 x-model="responses.Question7_BathBeforeOrAfterBreakFast"/>
                                        <span class="text-sm">નાસ્તા પછી</span>
                                    </label>
                                </div>
                            </div>

                            {{-- Q8 --}}
                        @elseif($i == 8)
                            <x-filament::input.wrapper>
                                <x-slot name="label">8. નાસ્તો કેટલા વાગે કરો છો ?</x-slot>
                                <x-filament::input type="time" x-model="responses.Question8_BreakFastTime"/>
                            </x-filament::input.wrapper>

                            {{-- Q9 --}}
                        @elseif($i == 9)
                            <div class="space-y-3">
                                <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    9. સવારે નાસ્તો કરો છો ત્યારે ભૂખ લાગી હોય છે ?
                                </label>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2">
                                        <x-filament::input.radio value="Yes"
                                                                 x-model="responses.Question9_BreakFastYesNo"/>
                                        <span class="text-sm">Yes</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <x-filament::input.radio value="No"
                                                                 x-model="responses.Question9_BreakFastYesNo"/>
                                        <span class="text-sm">No</span>
                                    </label>
                                </div>
                            </div>

                            {{-- Q10 --}}
                        @elseif($i == 10)
                            <div class="space-y-3">
                                <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    10. ભૂખ લાગ્યા વગર નાસ્તો કરો છો ?
                                </label>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2">
                                        <x-filament::input.radio value="Yes"
                                                                 x-model="responses.Question10_BreakFastYesNo"/>
                                        <span class="text-sm">Yes</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <x-filament::input.radio value="No"
                                                                 x-model="responses.Question10_BreakFastYesNo"/>
                                        <span class="text-sm">No</span>
                                    </label>
                                </div>
                            </div>

                            {{-- Q11 --}}
                        @elseif($i == 11)
                            <div class="space-y-3">
                                <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    11. નાસ્તા મા શુ લ્યો છો ?
                                </label>
                                <div default="2" md="3" class="gap-3">
                                    @php
                                        $q11Opts = ['Tea'=>'ચા','Coffee'=>'કોફી','Milk'=>'દૂધ','Bhakhari'=>'ભાખરી/રોટલી','BhakhariKhari'=>'ભાખરી/રોટલી ખારી હોય','CoroBreakFast'=>'કોરો નાસ્તો','CarryWithOnion'=>'શાક લશન/ડુંગળી','Murmura'=>'મમરા/ચવાણું','Bread'=>'બ્રેડ/બિસ્કિટ','Chatani'=>'ચટણી/અથાણું','EveningFood'=>'સાંજનું વાસી','FryFood'=>'ફરસાણ'];
                                    @endphp
                                    @foreach($q11Opts as $key => $lbl)
                                        <label class="flex items-center gap-2">
                                            <x-filament::input.checkbox
                                                x-model="responses.Question11_{{ $key }}"/>
                                            <span
                                                class="text-sm text-gray-700 dark:text-gray-300">{{ $lbl }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Q12 --}}
                        @elseif($i == 12)
                            <div class="space-y-3">
                                <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    12. સવારે ફક્ત ચા કે દૂધ કે જ્યુશ પીવો છો ?
                                </label>
                                <div class="flex gap-4 mb-2">
                                    <label class="flex items-center gap-2">
                                        <x-filament::input.radio value="Yes"
                                                                 x-model="responses.Question12_TeaOnlyYesNo"/>
                                        <span class="text-sm">Yes</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <x-filament::input.radio value="No"
                                                                 x-model="responses.Question12_TeaOnlyYesNo"/>
                                        <span class="text-sm">No</span>
                                    </label>
                                </div>
                                <div default="1" md="2" class="gap-4">
                                    <x-filament::input.wrapper>
                                        <x-filament::input type="text" placeholder="એકલી ચા કપ મા"
                                                           x-model="responses.Question12_OnlyTea"/>
                                    </x-filament::input.wrapper>
                                    <x-filament::input.wrapper>
                                        <x-filament::input type="text" placeholder="એકલું દૂધ કપ મા"
                                                           x-model="responses.Question12_OnlyMilk"/>
                                    </x-filament::input.wrapper>
                                    <div class="md:col-span-2">
                                        <x-filament::input.wrapper>
                                            <x-filament::input type="text"
                                                               placeholder="એકલું જ્યુશ (કારેલા/દૂધી...)"
                                                               x-model="responses.Question12_OnlyJuice"/>
                                        </x-filament::input.wrapper>
                                    </div>
                                </div>
                            </div>

                            {{-- Q13 --}}
                        @elseif($i == 13)
                            <div class="space-y-3">
                                <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    13. સૂકો મેવો જેમકે કાજુ,બદામ,અખરોટ,અંજીર ખાવ છો ?
                                </label>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2">
                                        <x-filament::input.radio value="Yes"
                                                                 x-model="responses.Question13_NutsYesNo"/>
                                        <span class="text-sm">Yes</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <x-filament::input.radio value="No"
                                                                 x-model="responses.Question13_NutsYesNo"/>
                                        <span class="text-sm">No</span>
                                    </label>
                                </div>
                            </div>

                            {{-- Q14 --}}
                        @elseif($i == 14)
                            <x-filament::input.wrapper>
                                <x-slot name="label">14. નાસ્તા કે જમ્યા પછી કેટલુ પાણી પીવો છો ?</x-slot>
                                <x-filament::input.select x-model="responses.Question14_WaterAfterLunch">
                                    <option value="">Select Amount</option>
                                    <option value="None">None</option>
                                    <option value="Little">Little</option>
                                    <option value="Full">Full</option>
                                </x-filament::input.select>
                            </x-filament::input.wrapper>

                            {{-- Q15 --}}
                        @elseif($i == 15)
                            <div class="space-y-3">
                                <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    15. ધંધો/નોકરી કયા પ્રકાર ની કરો છો ?
                                </label>
                                <div default="2" class="gap-3">
                                    @foreach(['SettingJob'=>'બેસવા','StandingJob'=>'ઉભા રહેવા','TravellingJob'=>'મુસાફરી','SunLightJob'=>'તડકા મા','SettingRoomJob'=>'છાયા મા','AcJob'=>'એસી મા'] as $key => $lbl)
                                        <label class="flex items-center gap-2">
                                            <x-filament::input.checkbox
                                                x-model="responses.Question15_{{ $key }}"/>
                                            <span
                                                class="text-sm text-gray-700 dark:text-gray-300">{{ $lbl }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Q16 --}}
                        @elseif($i == 16)
                            <div class="space-y-3">
                                <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    16. સવારે 10-11 વાગ્યે ફ્રુટ કે બીજી કઈ વસ્તુ ખાવ છો ?
                                </label>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2">
                                        <x-filament::input.radio value="yes"
                                                                 x-model="responses.Question16_FruitYesNo"/>
                                        <span class="text-sm">Yes</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <x-filament::input.radio value="no"
                                                                 x-model="responses.Question16_FruitYesNo"/>
                                        <span class="text-sm">No</span>
                                    </label>
                                </div>
                                <x-filament::input.wrapper>
                                    <x-filament::input type="text" placeholder="Name of Fruit or Other"
                                                       x-model="responses.Question16_Fruits"/>
                                </x-filament::input.wrapper>
                            </div>

                            {{-- Q17 --}}
                        @elseif($i == 17)
                            <x-filament::input.wrapper>
                                <x-slot name="label">17. બોપરે કેટલા વાગે જમો છો ?</x-slot>
                                <x-filament::input type="time" x-model="responses.Question17_LunchTime"/>
                            </x-filament::input.wrapper>

                            {{-- Q18 --}}
                        @elseif($i == 18)
                            <div class="space-y-3">
                                <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    18. જમવાની આદતો
                                </label>
                                <div class="space-y-3 bg-gray-50 dark:bg-white/5 p-4 rounded-lg">
                                    <div class="flex justify-between items-center max-w-sm">
                                        <span class="text-sm">ભૂખ લાગી હોય છે ?</span>
                                        <div class="flex gap-3">
                                            <label class="flex items-center gap-1">
                                                <x-filament::input.radio value="yes"
                                                                         x-model="responses.Question18_LunchHugreyYesNo"/>
                                                <span class="text-sm">Y</span>
                                            </label>
                                            <label class="flex items-center gap-1">
                                                <x-filament::input.radio value="no"
                                                                         x-model="responses.Question18_LunchHugreyYesNo"/>
                                                <span class="text-sm">N</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center max-w-sm">
                                        <span class="text-sm">સમય થયો એટલે જમો છો ?</span>
                                        <div class="flex gap-3">
                                            <label class="flex items-center gap-1">
                                                <x-filament::input.radio value="yes"
                                                                         x-model="responses.Question18_TimeLunchYesNo"/>
                                                <span class="text-sm">Y</span>
                                            </label>
                                            <label class="flex items-center gap-1">
                                                <x-filament::input.radio value="no"
                                                                         x-model="responses.Question18_TimeLunchYesNo"/>
                                                <span class="text-sm">N</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Q19 --}}
                        @elseif($i == 19)
                            <div class="space-y-3">
                                <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    19. બપોરે શુ જમો છો ?
                                </label>
                                <div default="3" class="gap-3">
                                    @php
                                        $q19 = ['Guvar'=>'ગુવાર','Brijal'=>'રિગાણા','Tamato'=>'ટામેટા','Patato'=>'બટેટા','LadyFinger'=>'ભીંડો','Chana'=>'ચણા','Val'=>'વાલ','Vatana'=>'વટાણા','Adad'=>'અડદ','AdadPapad'=>'અડદ પાપડ','Dhosa'=>'ઢોસા','Marcha'=>'મરચા','ButterMilk'=>'છાશ','Curd'=>'દહીં','SugerCane'=>'ગોળ','Athanu'=>'અથાણું','DalBhat'=>'દાળ ભાત'];
                                    @endphp
                                    @foreach($q19 as $k => $l)
                                        <label class="flex items-center gap-2">
                                            <x-filament::input.checkbox
                                                x-model="responses.Question19_{{ $k }}"/>
                                            <span
                                                class="text-sm text-gray-700 dark:text-gray-300">{{ $l }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Q20 --}}
                        @elseif($i == 20)
                            <div class="space-y-3">
                                <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    20. દરરોજ અથવા વધારે શુ લો છો ?
                                </label>
                                <div default="3" class="gap-3">
                                    @php $q20 = ['Gol'=>'ગોળ','Curd'=>'દહીં','ButterMilk'=>'છાસ','Athanu'=>'અથાણું','Spicies'=>'મરચા','Chatani'=>'ચટણી','Garlic'=>'લસણ','Onion'=>'ડુંગળી','PalkhniBhaji'=>'પાલખ','AdadPapad'=>'પાપડ','Rices'=>'ભાત','Tikhu'=>'તીખું','Khatu'=>'ખાટુ','Sour'=>'ખારું','KoroNasto'=>'કોરો નાસ્તો','LunchSleep'=>'બપોર નિદ્રા','LatenightWakeup'=>'રાત્રી જાગરણ','Sweet'=>'મીઠાઈ']; @endphp
                                    @foreach($q20 as $k=>$l)
                                        <label class="flex items-center gap-2">
                                            <x-filament::input.checkbox
                                                x-model="responses.Question20_{{ $k }}"/>
                                            <span
                                                class="text-sm text-gray-700 dark:text-gray-300">{{ $l }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Q21 --}}
                        @elseif($i == 21)
                            <x-filament::input.wrapper>
                                <x-slot name="label">21. દરરોજ ની ટેવ (સોડા, આઇશક્રેમ, ફ્રેઝ પાણી..)?</x-slot>
                                <x-filament::input type="text" placeholder="વર્ણન કરો"
                                                   x-model="responses.Question21_Details"/>
                            </x-filament::input.wrapper>

                            {{-- Q22 --}}
                        @elseif($i == 22)
                            <div class="space-y-3">
                                <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    22. કાચા શાકભાજી ખાવ છો ?
                                </label>
                                <div default="3" class="gap-3">
                                    @php $q22 = ['Cabbage'=>'કોબી','Kakadi'=>'કાકડી','Tomato'=>'ટામેટા','Carrot'=>'ગાજર','LiliHatdar'=>'લીલી હળદળ','LadyFinger'=>'ભીંડો','SweetPatoto'=>'શકરિયા','LilaChana'=>'લીલા ચાના','LilaVatana'=>'લીલા વટાણા','LilaTuver'=>'લીલી તુવેર','LilaNuts'=>'લીલી સીંગ']; @endphp
                                    @foreach($q22 as $k=>$l)
                                        <label class="flex items-center gap-2">
                                            <x-filament::input.checkbox
                                                x-model="responses.Question22_{{ $k }}"/>
                                            <span
                                                class="text-sm text-gray-700 dark:text-gray-300">{{ $l }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Q23 --}}
                        @elseif($i == 23)
                            <div class="space-y-3">
                                <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    23. શાક માં લસણ,ડુંગળી,ટમેટો,લીંબુ,ગોળ નાખો છો ?
                                </label>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2">
                                        <x-filament::input.radio value="yes"
                                                                 x-model="responses.Question23_VegitableInvalidYesNo"/>
                                        <span class="text-sm">Yes</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <x-filament::input.radio value="no"
                                                                 x-model="responses.Question23_VegitableInvalidYesNo"/>
                                        <span class="text-sm">No</span>
                                    </label>
                                </div>
                            </div>

                            {{-- Q24 --}}
                        @elseif($i == 24)
                            <div class="space-y-3">
                                <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    24. જમ્યા પછી કઈ ખાવા-પીવા ની ટેવ છે ?
                                </label>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2">
                                        <x-filament::input.radio value="yes"
                                                                 x-model="responses.Question24_AfterEatingYesNo"/>
                                        <span class="text-sm">Yes</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <x-filament::input.radio value="no"
                                                                 x-model="responses.Question24_AfterEatingYesNo"/>
                                        <span class="text-sm">No</span>
                                    </label>
                                </div>
                                <div class="pt-2">
                                    <div default="3" class="gap-3">
                                        @foreach(['Mukhvas'=>'મુખવાસ','Nuts'=>'ખારીશીંગ','Daliya'=>'દાળિયા','Vatana'=>'વટાણા','Icecream'=>'આઇશક્રેમ','Fruit'=>'ફ્રૂટ','Soda'=>'સોડા'] as $k => $l)
                                            <label class="flex items-center gap-2">
                                                <x-filament::input.checkbox
                                                    x-model="responses.Question24_{{ $k }}"/>
                                                <span
                                                    class="text-sm text-gray-700 dark:text-gray-300">{{ $l }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- Q25 --}}
                        @elseif($i == 25)
                            <div class="space-y-3">
                                <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    25. ચાયનીઝ,સમોસા,ખમણ,પફ,સોડા,આઇશક્રેમ,બેકરી..?
                                </label>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2">
                                        <x-filament::input.radio value="yes"
                                                                 x-model="responses.Question25_JunkFoodYesNo"/>
                                        <span class="text-sm">Yes</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <x-filament::input.radio value="no"
                                                                 x-model="responses.Question25_JunkFoodYesNo"/>
                                        <span class="text-sm">No</span>
                                    </label>
                                </div>
                            </div>

                            {{-- Q26 --}}
                        @elseif($i == 26)
                            <div class="space-y-3">
                                <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    26. બપોરે શુવો છો ?
                                </label>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2">
                                        <x-filament::input.radio value="yes"
                                                                 x-model="responses.Question26_SleepAtNoon"/>
                                        <span class="text-sm">Yes</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <x-filament::input.radio value="no"
                                                                 x-model="responses.Question26_SleepAtNoon"/>
                                        <span class="text-sm">No</span>
                                    </label>
                                </div>
                                <x-filament::input.wrapper>
                                    <x-filament::input type="text" placeholder="Hours"
                                                       x-model="responses.Question26_TimeInHour"/>
                                </x-filament::input.wrapper>
                            </div>

                            {{-- Q27 --}}
                        @elseif($i == 27)
                            <div class="space-y-3">
                                <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    27. બપોરે જાગ્યા પછી ચા/કોફી/નાસ્તો ખાવ છો ?
                                </label>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2">
                                        <x-filament::input.radio value="yes"
                                                                 x-model="responses.Question27_LunchAfterTea"/>
                                        <span class="text-sm">Yes</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <x-filament::input.radio value="no"
                                                                 x-model="responses.Question27_LunchAfterTea"/>
                                        <span class="text-sm">No</span>
                                    </label>
                                </div>
                                <x-filament::input.wrapper>
                                    <x-filament::input type="text" placeholder="What do you eat?"
                                                       x-model="responses.Question27_Names"/>
                                </x-filament::input.wrapper>
                            </div>

                            {{-- Q28 --}}
                        @elseif($i == 28)
                            <x-filament::input.wrapper>
                                <x-slot name="label">28. સાંજે કેટલા વાગે જમો છો ?</x-slot>
                                <x-filament::input type="time" x-model="responses.Question28_EveningDinner"/>
                            </x-filament::input.wrapper>

                            {{-- Q29 --}}
                        @elseif($i == 29)
                            <div class="space-y-3">
                                <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    29. સાંજનું ભોજન
                                </label>
                                <div class="space-y-3">
                                    <x-filament::input.wrapper>
                                        <x-filament::input type="text" placeholder="સાંજે જમવા શુ લો છે ?"
                                                           x-model="responses.Question29_DinnerNames"/>
                                    </x-filament::input.wrapper>
                                    <x-filament::input.wrapper>
                                        <x-filament::input type="text" placeholder="દૂધ કેટલુ લો છે ? (In ML)"
                                                           x-model="responses.Question29_MilksInMl"/>
                                    </x-filament::input.wrapper>
                                    <div
                                        class="flex items-center gap-4 p-3 bg-gray-50 dark:bg-white/5 rounded-lg border border-gray-100 dark:border-white/5">
                                        <span class="text-sm">ખીચડી અને દૂધ ભેગું કરીને ?</span>
                                        <label class="flex items-center gap-2">
                                            <x-filament::input.radio value="yes"
                                                                     x-model="responses.Question29_KhichadiYesNo"/>
                                            <span class="text-sm">Y</span>
                                        </label>
                                        <label class="flex items-center gap-2">
                                            <x-filament::input.radio value="no"
                                                                     x-model="responses.Question29_KhichadiYesNo"/>
                                            <span class="text-sm">N</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Q30 --}}
                        @elseif($i == 30)
                            <div class="space-y-3">
                                <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    30. સાંજે જમ્યા પછી રાત્રે નાસ્તો/ફ્રૂટ/દૂધ લો છો ?
                                </label>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2">
                                        <x-filament::input.radio value="yes"
                                                                 x-model="responses.Question30_AfterDinnerSnackYesNo"/>
                                        <span class="text-sm">Yes</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <x-filament::input.radio value="no"
                                                                 x-model="responses.Question30_AfterDinnerSnackYesNo"/>
                                        <span class="text-sm">No</span>
                                    </label>
                                </div>
                            </div>

                            {{-- Q31 --}}
                        @elseif($i == 31)
                            <x-filament::input.wrapper>
                                <x-slot name="label">31. પાણી આખો દિવસ મા કેટલું પીવો છો ? (ML)</x-slot>
                                <x-filament::input type="text" x-model="responses.Question31_WaterInDay"/>
                            </x-filament::input.wrapper>

                            {{-- Q32 --}}
                        @elseif($i == 32)
                            <div class="space-y-3">
                                <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    32. વિરોધ આહાર કરો છો ?
                                </label>
                                <div default="1" class="gap-2">
                                    @php $q32 = ['KhichadiMilk'=>'ખીચડી અને દૂધ','Garlic'=>'લસણ/ડુંગળી + દૂધ','FruitMilk'=>'ફ્રૂટ + દૂધ','FruitSalad'=>'ફ્રૂટ સલાડ','ButterAndMilk'=>'છાસ અને દૂધ','ChatniWithMilk'=>'ચટણી સાથે દૂધ','HotWaterHoony'=>'ગરમ પાણી અને મધ','UnSeasonalFruit'=>'ઋતુ સિવાય ના ફળો','TakeFoodWithoutLatrine'=>'સંડાસ ગયા વગર જમવું']; @endphp
                                    @foreach($q32 as $k=>$l)
                                        <label class="flex items-center gap-2">
                                            <x-filament::input.checkbox
                                                x-model="responses.Question32_{{ $k }}"/>
                                            <span
                                                class="text-sm text-gray-700 dark:text-gray-300">{{ $l }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Q33 --}}
                        @elseif($i == 33)
                            <div class="space-y-3">
                                <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    33. ફ્રૂટ ક્યા ખાવ છો ?
                                </label>
                                <div default="3" class="gap-2">
                                    @php $q33 = ['Banana'=>'કેળા','Apple'=>'સફરજન','Graps'=>'દ્રાક્ષ','WaterMeleon'=>'તરબૂચ','Coconut'=>'નાળિયર','Chiku'=>'ચીકુ','Pomegranate'=>'દાડમ','Mongo'=>'કેરી','Papiya'=>'પપેયો','Orange'=>'સંતરા','Gooseberry'=>'જામફળ','Jambu'=>'જાંબુ','SweetTeti'=>'સાકર ટેટી','SugarCane'=>'શેરડી','Stroberry'=>'સ્ટ્રોબરી','Ambala'=>'આંબળા','Kiwi'=>'કીવી','DragoanFruit'=>'દ્રગન','Pinnepal'=>'પાઈનેપલ']; @endphp
                                    @foreach($q33 as $k=>$l)
                                        <label class="flex items-center gap-2">
                                            <x-filament::input.checkbox
                                                x-model="responses.Question33_{{ $k }}"/>
                                            <span
                                                class="text-sm text-gray-700 dark:text-gray-300">{{ $l }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Q34 --}}
                        @elseif($i == 34)
                            <x-filament::input.wrapper>
                                <x-slot name="label">34. આ સિવાય ની એક એવી ટેવ જે દરરોજ કરો છો ?</x-slot>
                                <x-filament::input type="text" x-model="responses.Question34_Habbit"/>
                            </x-filament::input.wrapper>

                            {{-- Q35 --}}
                        @elseif($i == 35)
                            <div class="space-y-3">
                                <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    35. બીજો કઈ રોગ છે ?
                                </label>
                                <div class="space-y-3">
                                    <x-filament::input.wrapper>
                                        <x-filament::input type="text" placeholder="Disease Name"
                                                           x-model="responses.Question35_OtherDisease"/>
                                    </x-filament::input.wrapper>
                                    <x-filament::input.wrapper>
                                        <x-filament::input type="text" placeholder="Medicines"
                                                           x-model="responses.Question35_Medicines"/>
                                    </x-filament::input.wrapper>
                                    <x-filament::input.wrapper>
                                        <x-filament::input type="text" placeholder="Duration (Years/Months)"
                                                           x-model="responses.Question35_DiseaseTime"/>
                                    </x-filament::input.wrapper>
                                </div>
                            </div>

                        @endif
                    </div>

                    {{-- RIGHT COLUMN: Status Sidebar --}}
                    <div
                        class="border-t md:border-t-0 md:border-l border-gray-200 dark:border-white/10 p-6 md:w-1/2 bg-gray-50/50 dark:bg-white/5">
                        <div class="space-y-6 flex gap-4">

                            {{-- Dosha Section --}}
                            <div>
                                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">
                                    Dosha
                                </div>
                                <div class="flex  gap-3">
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <x-filament::input.checkbox x-model="responses.q{{$i}}_vat"/>
                                        <span
                                            class="text-sm font-medium text-gray-600 dark:text-gray-300 group-hover:text-primary-600">VAT</span>
                                    </label>
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <x-filament::input.checkbox x-model="responses.q{{$i}}_pit"/>
                                        <span
                                            class="text-sm font-medium text-gray-600 dark:text-gray-300 group-hover:text-primary-600">PIT</span>
                                    </label>
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <x-filament::input.checkbox x-model="responses.q{{$i}}_kuf"/>
                                        <span
                                            class="text-sm font-medium text-gray-600 dark:text-gray-300 group-hover:text-primary-600">KUF</span>
                                    </label>
                                </div>
                            </div>

                            <div class="h-px bg-gray-200 dark:bg-white/10"></div>

                            {{-- Status Section --}}
                            <div>
                                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">
                                    Status
                                </div>
                                <div class="flex gap-3">
                                    <label
                                        class="flex items-center gap-3 cursor-pointer p-2 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors border border-transparent hover:border-green-200">
                                        <x-filament::input.radio value="HitKar" name="q{{$i}}_st"
                                                                 x-model="responses.q{{$i}}_status"/>
                                        <span class="text-sm font-bold text-green-600">હિતકર</span>
                                    </label>
                                    <label
                                        class="flex items-center gap-3 cursor-pointer p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors border border-transparent hover:border-red-200">
                                        <x-filament::input.radio value="AhitKar" name="q{{$i}}_st"
                                                                 x-model="responses.q{{$i}}_status"/>
                                        <span class="text-sm font-bold text-red-600">અહિતકર</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- SUMMARY FOOTER --}}
        <div class="sticky bottom-4 z-10 mx-auto w-full">
            <div
                class="rounded-xl bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm border border-gray-200 dark:border-white/10 shadow-lg p-4 ring-1 ring-gray-950/5">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-bold text-gray-950 dark:text-white uppercase tracking-wide">Analysis
                        Summary</h3>
                    <span class="text-xs text-gray-500">Auto-calculated</span>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                    <div
                        class="p-3 bg-gray-50 dark:bg-white/5 rounded-lg border border-gray-100 dark:border-white/5 text-center">
                        <div class="text-[10px] font-bold text-gray-500 uppercase">VAT</div>
                        <div class="text-2xl font-bold text-primary-600" x-text="totals.vat">0</div>
                    </div>

                    <div
                        class="p-3 bg-gray-50 dark:bg-white/5 rounded-lg border border-gray-100 dark:border-white/5 text-center">
                        <div class="text-[10px] font-bold text-gray-500 uppercase">PIT</div>
                        <div class="text-2xl font-bold text-primary-600" x-text="totals.pit">0</div>
                    </div>

                    <div
                        class="p-3 bg-gray-50 dark:bg-white/5 rounded-lg border border-gray-100 dark:border-white/5 text-center">
                        <div class="text-[10px] font-bold text-gray-500 uppercase">KUF</div>
                        <div class="text-2xl font-bold text-primary-600" x-text="totals.kuf">0</div>
                    </div>

                    <div
                        class="p-3 bg-green-50 dark:bg-green-500/10 rounded-lg border border-green-200 dark:border-green-500/20 text-center">
                        <div class="text-[10px] font-bold text-green-600 uppercase">HitKar</div>
                        <div class="text-2xl font-bold text-green-700 dark:text-green-500" x-text="totals.hit">
                            0
                        </div>
                    </div>

                    <div
                        class="p-3 bg-red-50 dark:bg-red-500/10 rounded-lg border border-red-200 dark:border-red-500/20 text-center">
                        <div class="text-[10px] font-bold text-red-600 uppercase">AhitKar</div>
                        <div class="text-2xl font-bold text-red-700 dark:text-red-500" x-text="totals.ahit">0
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-dynamic-component>
