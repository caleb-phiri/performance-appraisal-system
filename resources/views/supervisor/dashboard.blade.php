<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor Dashboard - MOIC</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Production Tailwind CSS - Built version -->
    <style>
        /*! tailwindcss v3.4.1 | MIT License | https://tailwindcss.com */
        *,:after,:before{box-sizing:border-box;border:0 solid #e5e7eb}:after,:before{--tw-content:""}:host,html{line-height:1.5;-webkit-text-size-adjust:100%;-moz-tab-size:4;-o-tab-size:4;tab-size:4;font-family:ui-sans-serif,system-ui,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;font-feature-settings:normal;font-variation-settings:normal;-webkit-tap-highlight-color:transparent}body{margin:0;line-height:inherit}hr{height:0;color:inherit;border-top-width:1px}abbr:where([title]){-webkit-text-decoration:underline dotted;text-decoration:underline dotted}h1,h2,h3,h4,h5,h6{font-size:inherit;font-weight:inherit}a{color:inherit;text-decoration:inherit}b,strong{font-weight:bolder}code,kbd,pre,samp{font-family:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,Liberation Mono,Courier New,monospace;font-feature-settings:normal;font-variation-settings:normal;font-size:1em}small{font-size:80%}sub,sup{font-size:75%;line-height:0;position:relative;vertical-align:baseline}sub{bottom:-.25em}sup{top:-.5em}table{text-indent:0;border-color:inherit;border-collapse:collapse}button,input,optgroup,select,textarea{font-family:inherit;font-feature-settings:inherit;font-variation-settings:inherit;font-size:100%;font-weight:inherit;line-height:inherit;color:inherit;margin:0;padding:0}button,select{text-transform:none}[type=button],[type=reset],[type=submit],button{-webkit-appearance:button;background-color:transparent;background-image:none}:-moz-focusring{outline:auto}:-moz-ui-invalid{box-shadow:none}progress{vertical-align:baseline}::-webkit-inner-spin-button,::-webkit-outer-spin-button{height:auto}[type=search]{-webkit-appearance:textfield;outline-offset:-2px}::-webkit-search-decoration{-webkit-appearance:none}::-webkit-file-upload-button{-webkit-appearance:button;font:inherit}summary{display:list-item}blockquote,dd,dl,figure,h1,h2,h3,h4,h5,h6,hr,p,pre{margin:0}fieldset{margin:0}fieldset,legend{padding:0}menu,ol,ul{list-style:none;margin:0;padding:0}dialog{padding:0}textarea{resize:vertical}input::-moz-placeholder,textarea::-moz-placeholder{opacity:1;color:#9ca3af}input::placeholder,textarea::placeholder{opacity:1;color:#9ca3af}[role=button],button{cursor:pointer}:disabled{cursor:default}audio,canvas,embed,iframe,img,object,svg,video{display:block;vertical-align:middle}img,video{max-width:100%;height:auto}[hidden]{display:none}*,::backdrop,:after,:before{--tw-border-spacing-x:0;--tw-border-spacing-y:0;--tw-translate-x:0;--tw-translate-y:0;--tw-rotate:0;--tw-skew-x:0;--tw-skew-y:0;--tw-scale-x:1;--tw-scale-y:1;--tw-pan-x: ;--tw-pan-y: ;--tw-pinch-zoom: ;--tw-scroll-snap-strictness:proximity;--tw-gradient-from-position: ;--tw-gradient-via-position: ;--tw-gradient-to-position: ;--tw-ordinal: ;--tw-slashed-zero: ;--tw-numeric-figure: ;--tw-numeric-spacing: ;--tw-numeric-fraction: ;--tw-ring-inset: ;--tw-ring-offset-width:0px;--tw-ring-offset-color:#fff;--tw-ring-color:#3b82f680;--tw-ring-offset-shadow:0 0 #0000;--tw-ring-shadow:0 0 #0000;--tw-shadow:0 0 #0000;--tw-shadow-colored:0 0 #0000;--tw-blur: ;--tw-brightness: ;--tw-contrast: ;--tw-grayscale: ;--tw-hue-rotate: ;--tw-invert: ;--tw-saturate: ;--tw-sepia: ;--tw-drop-shadow: ;--tw-backdrop-blur: ;--tw-backdrop-brightness: ;--tw-backdrop-contrast: ;--tw-backdrop-grayscale: ;--tw-backdrop-hue-rotate: ;--tw-backdrop-invert: ;--tw-backdrop-opacity: ;--tw-backdrop-saturate: ;--tw-backdrop-sepia: }.container{width:100%}@media (min-width:640px){.container{max-width:640px}}@media (min-width:768px){.container{max-width:768px}}@media (min-width:1024px){.container{max-width:1024px}}@media (min-width:1280px){.container{max-width:1280px}}@media (min-width:1536px){.container{max-width:1536px}}.sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border-width:0}.pointer-events-none{pointer-events:none}.visible{visibility:visible}.fixed{position:fixed}.absolute{position:absolute}.relative{position:relative}.inset-0{inset:0}.inset-y-0{top:0;bottom:0}.bottom-6{bottom:1.5rem}.left-0{left:0}.right-4{right:1rem}.right-6{right:1.5rem}.top-4{top:1rem}.-right-2{right:-0.5rem}.-top-2{top:-0.5rem}.z-50{z-index:50}.col-span-3{grid-column:span 3/span 3}.m-0{margin:0}.mx-auto{margin-left:auto;margin-right:auto}.my-2{margin-top:.5rem;margin-bottom:.5rem}.mb-1{margin-bottom:.25rem}.mb-2{margin-bottom:.5rem}.mb-3{margin-bottom:.75rem}.mb-4{margin-bottom:1rem}.mb-6{margin-bottom:1.5rem}.mb-8{margin-bottom:2rem}.ml-1{margin-left:.25rem}.ml-2{margin-left:.5rem}.ml-4{margin-left:1rem}.ml-6{margin-left:1.5rem}.mr-1{margin-right:.25rem}.mr-2{margin-right:.5rem}.mr-3{margin-right:.75rem}.mr-4{margin-right:1rem}.mr-6{margin-right:1.5rem}.mt-1{margin-top:.25rem}.mt-2{margin-top:.5rem}.mt-4{margin-top:1rem}.mt-6{margin-top:1.5rem}.mt-8{margin-top:2rem}.mt-10{margin-top:2.5rem}.line-clamp-2{overflow:hidden;display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:2}.block{display:block}.inline-block{display:inline-block}.flex{display:flex}.inline-flex{display:inline-flex}.table{display:table}.grid{display:grid}.hidden{display:none}.h-1{height:.25rem}.h-1\.5{height:.375rem}.h-10{height:2.5rem}.h-12{height:3rem}.h-14{height:3.5rem}.h-2{height:.5rem}.h-2\.5{height:.625rem}.h-20{height:5rem}.h-24{height:6rem}.h-3{height:.75rem}.h-4{height:1rem}.h-5{height:1.25rem}.h-6{height:1.5rem}.h-8{height:2rem}.h-9{height:2.25rem}.h-96{height:24rem}.h-\[90vh\]{height:90vh}.max-h-96{max-height:24rem}.max-h-\[90vh\]{max-height:90vh}.min-h-screen{min-height:100vh}.w-1\/3{width:33.333333%}.w-10{width:2.5rem}.w-12{width:3rem}.w-14{width:3.5rem}.w-16{width:4rem}.w-2{width:.5rem}.w-20{width:5rem}.w-24{width:6rem}.w-3{width:.75rem}.w-4{width:1rem}.w-5{width:1.25rem}.w-6{width:1.5rem}.w-8{width:2rem}.w-9{width:2.25rem}.w-auto{width:auto}.w-full{width:100%}.min-w-0{min-width:0}.min-w-\[120px\]{min-width:120px}.min-w-\[130px\]{min-width:130px}.min-w-\[140px\]{min-width:140px}.min-w-\[160px\]{min-width:160px}.min-w-\[180px\]{min-width:180px}.min-w-full{min-width:100%}.max-w-4xl{max-width:56rem}.max-w-7xl{max-width:80rem}.max-w-md{max-width:28rem}.flex-1{flex:1 1 0%}.flex-shrink-0{flex-shrink:0}.transform{transform:translate(var(--tw-translate-x),var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y))}.animate-spin{animation:spin 1s linear infinite}@keyframes spin{to{transform:rotate(1turn)}}.cursor-pointer{cursor:pointer}.resize{resize:both}.list-inside{list-style-position:inside}.list-disc{list-style-type:disc}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}.grid-cols-3{grid-template-columns:repeat(3,minmax(0,1fr))}.grid-cols-4{grid-template-columns:repeat(4,minmax(0,1fr))}.flex-col{flex-direction:column}.flex-wrap{flex-wrap:wrap}.items-start{align-items:flex-start}.items-end{align-items:flex-end}.items-center{align-items:center}.justify-end{justify-content:flex-end}.justify-center{justify-content:center}.justify-between{justify-content:space-between}.gap-2{gap:.5rem}.gap-4{gap:1rem}.gap-5{gap:1.25rem}.gap-6{gap:1.5rem}.gap-8{gap:2rem}.space-x-2>:not([hidden])~:not([hidden]){--tw-space-x-reverse:0;margin-right:calc(.5rem*var(--tw-space-x-reverse));margin-left:calc(.5rem*(1 - var(--tw-space-x-reverse)))}.space-x-3>:not([hidden])~:not([hidden]){--tw-space-x-reverse:0;margin-right:calc(.75rem*var(--tw-space-x-reverse));margin-left:calc(.75rem*(1 - var(--tw-space-x-reverse)))}.space-x-4>:not([hidden])~:not([hidden]){--tw-space-x-reverse:0;margin-right:calc(1rem*var(--tw-space-x-reverse));margin-left:calc(1rem*(1 - var(--tw-space-x-reverse)))}.space-x-6>:not([hidden])~:not([hidden]){--tw-space-x-reverse:0;margin-right:calc(1.5rem*var(--tw-space-x-reverse));margin-left:calc(1.5rem*(1 - var(--tw-space-x-reverse)))}.space-y-2>:not([hidden])~:not([hidden]){--tw-space-y-reverse:0;margin-top:calc(.5rem*(1 - var(--tw-space-y-reverse)));margin-bottom:calc(.5rem*var(--tw-space-y-reverse))}.space-y-3>:not([hidden])~:not([hidden]){--tw-space-y-reverse:0;margin-top:calc(.75rem*(1 - var(--tw-space-y-reverse)));margin-bottom:calc(.75rem*var(--tw-space-y-reverse))}.space-y-4>:not([hidden])~:not([hidden]){--tw-space-y-reverse:0;margin-top:calc(1rem*(1 - var(--tw-space-y-reverse)));margin-bottom:calc(1rem*var(--tw-space-y-reverse))}.divide-x>:not([hidden])~:not([hidden]){--tw-divide-x-reverse:0;border-right-width:calc(1px*var(--tw-divide-x-reverse));border-left-width:calc(1px*(1 - var(--tw-divide-x-reverse)))}.divide-y>:not([hidden])~:not([hidden]){--tw-divide-y-reverse:0;border-top-width:calc(1px*(1 - var(--tw-divide-y-reverse)));border-bottom-width:calc(1px*var(--tw-divide-y-reverse))}.divide-gray-200>:not([hidden])~:not([hidden]){--tw-divide-opacity:1;border-color:rgb(229 231 235/var(--tw-divide-opacity))}.overflow-hidden{overflow:hidden}.overflow-x-auto{overflow-x:auto}.overflow-y-auto{overflow-y:auto}.truncate{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.whitespace-nowrap{white-space:nowrap}.rounded{border-radius:.25rem}.rounded-2xl{border-radius:1rem}.rounded-full{border-radius:9999px}.rounded-lg{border-radius:.5rem}.rounded-xl{border-radius:.75rem}.border{border-width:1px}.border-2{border-width:2px}.border-b{border-bottom-width:1px}.border-l-4{border-left-width:4px}.border-t{border-top-width:1px}.border-amber-200{border-color:rgb(253 230 138/var(--tw-border-opacity))}.border-blue-200{border-color:rgb(191 219 254/var(--tw-border-opacity))}.border-gray-100{border-color:rgb(243 244 246/var(--tw-border-opacity))}.border-gray-200{border-color:rgb(229 231 235/var(--tw-border-opacity))}.border-gray-300{border-color:rgb(209 213 219/var(--tw-border-opacity))}.border-green-200{border-color:rgb(187 247 208/var(--tw-border-opacity))}.border-green-500{border-color:rgb(34 197 94/var(--tw-border-opacity))}.border-indigo-200{border-color:rgb(199 210 254/var(--tw-border-opacity))}.border-purple-200{border-color:rgb(233 213 255/var(--tw-border-opacity))}.border-red-500{border-color:rgb(239 68 68/var(--tw-border-opacity))}.border-yellow-200{border-color:rgb(254 240 138/var(--tw-border-opacity))}.border-yellow-500{border-color:rgb(234 179 8/var(--tw-border-opacity))}.border-moic-accent{border-color:#e7581c}.border-moic-navy-light{border-color:#2a1d9e}.bg-amber-100{background-color:rgb(254 243 199/var(--tw-bg-opacity))}.bg-black{background-color:#000}.bg-blue-100{background-color:rgb(219 234 254/var(--tw-bg-opacity))}.bg-blue-50{background-color:rgb(239 246 255/var(--tw-bg-opacity))}.bg-blue-500{background-color:rgb(59 130 246/var(--tw-bg-opacity))}.bg-blue-600{background-color:rgb(37 99 235/var(--tw-bg-opacity))}.bg-gray-100{background-color:rgb(243 244 246/var(--tw-bg-opacity))}.bg-gray-200{background-color:rgb(229 231 235/var(--tw-bg-opacity))}.bg-gray-50{background-color:rgb(249 250 251/var(--tw-bg-opacity))}.bg-green-100{background-color:rgb(220 252 231/var(--tw-bg-opacity))}.bg-green-50{background-color:rgb(240 253 244/var(--tw-bg-opacity))}.bg-green-500{background-color:rgb(34 197 94/var(--tw-bg-opacity))}.bg-green-600{background-color:rgb(22 163 74/var(--tw-bg-opacity))}.bg-indigo-100{background-color:rgb(224 231 255/var(--tw-bg-opacity))}.bg-indigo-50{background-color:rgb(238 242 255/var(--tw-bg-opacity))}.bg-orange-100{background-color:rgb(255 237 213/var(--tw-bg-opacity))}.bg-pink-100{background-color:rgb(252 231 243/var(--tw-bg-opacity))}.bg-purple-100{background-color:rgb(243 232 255/var(--tw-bg-opacity))}.bg-purple-50{background-color:rgb(250 245 255/var(--tw-bg-opacity))}.bg-red-100{background-color:rgb(254 226 226/var(--tw-bg-opacity))}.bg-red-50{background-color:rgb(254 242 242/var(--tw-bg-opacity))}.bg-red-500{background-color:rgb(239 68 68/var(--tw-bg-opacity))}.bg-red-600{background-color:rgb(220 38 38/var(--tw-bg-opacity))}.bg-white{background-color:#fff}.bg-white\/10{background-color:#ffffff1a}.bg-white\/20{background-color:#fff3}.bg-white\/30{background-color:#ffffff4d}.bg-yellow-100{background-color:rgb(254 249 195/var(--tw-bg-opacity))}.bg-yellow-50{background-color:rgb(254 252 232/var(--tw-bg-opacity))}.bg-yellow-500{background-color:rgb(234 179 8/var(--tw-bg-opacity))}.bg-gradient-to-br{background-image:linear-gradient(to bottom right,var(--tw-gradient-stops))}.bg-gradient-to-r{background-image:linear-gradient(to right,var(--tw-gradient-stops))}.from-amber-100{--tw-gradient-from:#fef3c7 var(--tw-gradient-from-position);--tw-gradient-to:#fef3c700 var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-from),var(--tw-gradient-to)}.from-blue-100{--tw-gradient-from:#dbeafe var(--tw-gradient-from-position);--tw-gradient-to:#dbeafe00 var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-from),var(--tw-gradient-to)}.from-blue-50{--tw-gradient-from:#eff6ff var(--tw-gradient-from-position);--tw-gradient-to:#eff6ff00 var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-from),var(--tw-gradient-to)}.from-blue-500{--tw-gradient-from:#3b82f6 var(--tw-gradient-from-position);--tw-gradient-to:#3b82f600 var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-from),var(--tw-gradient-to)}.from-gray-100{--tw-gradient-from:#f3f4f6 var(--tw-gradient-from-position);--tw-gradient-to:#f3f4f600 var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-from),var(--tw-gradient-to)}.from-gray-50{--tw-gradient-from:#f9fafb var(--tw-gradient-from-position);--tw-gradient-to:#f9fafb00 var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-from),var(--tw-gradient-to)}.from-green-100{--tw-gradient-from:#dcfce7 var(--tw-gradient-from-position);--tw-gradient-to:#dcfce700 var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-from),var(--tw-gradient-to)}.from-green-50{--tw-gradient-from:#f0fdf4 var(--tw-gradient-from-position);--tw-gradient-to:#f0fdf400 var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-from),var(--tw-gradient-to)}.from-green-500{--tw-gradient-from:#22c55e var(--tw-gradient-from-position);--tw-gradient-to:#22c55e00 var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-from),var(--tw-gradient-to)}.from-indigo-100{--tw-gradient-from:#e0e7ff var(--tw-gradient-from-position);--tw-gradient-to:#e0e7ff00 var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-from),var(--tw-gradient-to)}.from-indigo-50{--tw-gradient-from:#eef2ff var(--tw-gradient-from-position);--tw-gradient-to:#eef2ff00 var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-from),var(--tw-gradient-to)}.from-indigo-500{--tw-gradient-from:#6366f1 var(--tw-gradient-from-position);--tw-gradient-to:#6366f100 var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-from),var(--tw-gradient-to)}.from-purple-100{--tw-gradient-from:#f3e8ff var(--tw-gradient-from-position);--tw-gradient-to:#f3e8ff00 var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-from),var(--tw-gradient-to)}.from-purple-50{--tw-gradient-from:#faf5ff var(--tw-gradient-from-position);--tw-gradient-to:#faf5ff00 var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-from),var(--tw-gradient-to)}.from-purple-500{--tw-gradient-from:#a855f7 var(--tw-gradient-from-position);--tw-gradient-to:#a855f700 var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-from),var(--tw-gradient-to)}.from-red-100{--tw-gradient-from:#fee2e2 var(--tw-gradient-from-position);--tw-gradient-to:#fee2e200 var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-from),var(--tw-gradient-to)}.from-red-50{--tw-gradient-from:#fef2f2 var(--tw-gradient-from-position);--tw-gradient-to:#fef2f200 var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-from),var(--tw-gradient-to)}.from-red-500{--tw-gradient-from:#ef4444 var(--tw-gradient-from-position);--tw-gradient-to:#ef444400 var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-from),var(--tw-gradient-to)}.from-white{--tw-gradient-from:#fff var(--tw-gradient-from-position);--tw-gradient-to:#fff0 var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-from),var(--tw-gradient-to)}.from-yellow-100{--tw-gradient-from:#fef9c3 var(--tw-gradient-from-position);--tw-gradient-to:#fef9c300 var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-from),var(--tw-gradient-to)}.from-yellow-50{--tw-gradient-from:#fefce8 var(--tw-gradient-from-position);--tw-gradient-to:#fefce800 var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-from),var(--tw-gradient-to)}.from-yellow-500{--tw-gradient-from:#eab308 var(--tw-gradient-from-position);--tw-gradient-to:#eab30800 var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-from),var(--tw-gradient-to)}.via-purple-600{--tw-gradient-to:#9333ea00;--tw-gradient-stops:var(--tw-gradient-from),#9333ea var(--tw-gradient-via-position),var(--tw-gradient-to)}.to-amber-200{--tw-gradient-to:#fed7aa var(--tw-gradient-to-position)}.to-blue-100{--tw-gradient-to:#dbeafe var(--tw-gradient-to-position)}.to-blue-200{--tw-gradient-to:#bfdbfe var(--tw-gradient-to-position)}.to-blue-600{--tw-gradient-to:#2563eb var(--tw-gradient-to-position)}.to-blue-700{--tw-gradient-to:#1d4ed8 var(--tw-gradient-to-position)}.to-blue-800{--tw-gradient-to:#1e40af var(--tw-gradient-to-position)}.to-gray-100{--tw-gradient-to:#f3f4f6 var(--tw-gradient-to-position)}.to-gray-200{--tw-gradient-to:#e5e7eb var(--tw-gradient-to-position)}.to-green-100{--tw-gradient-to:#dcfce7 var(--tw-gradient-to-position)}.to-green-200{--tw-gradient-to:#bbf7d0 var(--tw-gradient-to-position)}.to-green-600{--tw-gradient-to:#16a34a var(--tw-gradient-to-position)}.to-indigo-100{--tw-gradient-to:#e0e7ff var(--tw-gradient-to-position)}.to-indigo-200{--tw-gradient-to:#c7d2fe var(--tw-gradient-to-position)}.to-indigo-600{--tw-gradient-to:#4f46e5 var(--tw-gradient-to-position)}.to-purple-100{--tw-gradient-to:#f3e8ff var(--tw-gradient-to-position)}.to-purple-200{--tw-gradient-to:#e9d5ff var(--tw-gradient-to-position)}.to-purple-600{--tw-gradient-to:#9333ea var(--tw-gradient-to-position)}.to-red-600{--tw-gradient-to:#dc2626 var(--tw-gradient-to-position)}.to-white{--tw-gradient-to:#fff var(--tw-gradient-to-position)}.to-yellow-100{--tw-gradient-to:#fef9c3 var(--tw-gradient-to-position)}.to-yellow-200{--tw-gradient-to:#fef08a var(--tw-gradient-to-position)}.to-yellow-600{--tw-gradient-to:#ca8a04 var(--tw-gradient-to-position)}.bg-opacity-50{--tw-bg-opacity:0.5}.p-1{padding:.25rem}.p-2{padding:.5rem}.p-2\.5{padding:.625rem}.p-3{padding:.75rem}.p-4{padding:1rem}.p-5{padding:1.25rem}.p-6{padding:1.5rem}.p-8{padding:2rem}.px-2{padding-left:.5rem;padding-right:.5rem}.px-3{padding-left:.75rem;padding-right:.75rem}.px-4{padding-left:1rem;padding-right:1rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.px-8{padding-left:2rem;padding-right:2rem}.py-0{padding-top:0;padding-bottom:0}.py-0\.5{padding-top:.125rem;padding-bottom:.125rem}.py-1{padding-top:.25rem;padding-bottom:.25rem}.py-1\.5{padding-top:.375rem;padding-bottom:.375rem}.py-12{padding-top:3rem;padding-bottom:3rem}.py-16{padding-top:4rem;padding-bottom:4rem}.py-2{padding-top:.5rem;padding-bottom:.5rem}.py-2\.5{padding-top:.625rem;padding-bottom:.625rem}.py-3{padding-top:.75rem;padding-bottom:.75rem}.py-4{padding-top:1rem;padding-bottom:1rem}.py-5{padding-top:1.25rem;padding-bottom:1.25rem}.py-6{padding-top:1.5rem;padding-bottom:1.5rem}.py-8{padding-top:2rem;padding-bottom:2rem}.pb-4{padding-bottom:1rem}.pl-10{padding-left:2.5rem}.pl-12{padding-left:3rem}.pl-3{padding-left:.75rem}.pl-4{padding-left:1rem}.pr-10{padding-right:2.5rem}.pr-4{padding-right:1rem}.pt-4{padding-top:1rem}.pt-6{padding-top:1.5rem}.pt-8{padding-top:2rem}.text-left{text-align:left}.text-center{text-align:center}.text-right{text-align:right}.align-top{vertical-align:top}.text-2xl{font-size:1.5rem;line-height:2rem}.text-3xl{font-size:1.875rem;line-height:2.25rem}.text-4xl{font-size:2.25rem;line-height:2.5rem}.text-lg{font-size:1.125rem;line-height:1.75rem}.text-sm{font-size:.875rem;line-height:1.25rem}.text-xl{font-size:1.25rem;line-height:1.75rem}.text-xs{font-size:.75rem;line-height:1rem}.font-bold{font-weight:700}.font-medium{font-weight:500}.font-semibold{font-weight:600}.uppercase{text-transform:uppercase}.capitalize{text-transform:capitalize}.leading-5{line-height:1.25rem}.leading-6{line-height:1.5rem}.tracking-wider{letter-spacing:.05em}.text-amber-600{color:rgb(217 119 6/var(--tw-text-opacity))}.text-amber-700{color:rgb(180 83 9/var(--tw-text-opacity))}.text-blue-100{color:rgb(219 234 254/var(--tw-text-opacity))}.text-blue-200{color:rgb(191 219 254/var(--tw-text-opacity))}.text-blue-300{color:rgb(147 197 253/var(--tw-text-opacity))}.text-blue-600{color:rgb(37 99 235/var(--tw-text-opacity))}.text-blue-700{color:rgb(29 78 216/var(--tw-text-opacity))}.text-blue-800{color:rgb(30 64 175/var(--tw-text-opacity))}.text-gray-300{color:rgb(209 213 219/var(--tw-text-opacity))}.text-gray-400{color:rgb(156 163 175/var(--tw-text-opacity))}.text-gray-500{color:rgb(107 114 128/var(--tw-text-opacity))}.text-gray-600{color:rgb(75 85 99/var(--tw-text-opacity))}.text-gray-700{color:rgb(55 65 81/var(--tw-text-opacity))}.text-gray-800{color:rgb(31 41 55/var(--tw-text-opacity))}.text-gray-900{color:rgb(17 24 39/var(--tw-text-opacity))}.text-green-500{color:rgb(34 197 94/var(--tw-text-opacity))}.text-green-600{color:rgb(22 163 74/var(--tw-text-opacity))}.text-green-700{color:rgb(21 128 61/var(--tw-text-opacity))}.text-green-800{color:rgb(22 101 52/var(--tw-text-opacity))}.text-indigo-500{color:rgb(99 102 241/var(--tw-text-opacity))}.text-indigo-600{color:rgb(79 70 229/var(--tw-text-opacity))}.text-indigo-700{color:rgb(67 56 202/var(--tw-text-opacity))}.text-indigo-800{color:rgb(55 48 163/var(--tw-text-opacity))}.text-pink-800{color:rgb(157 23 77/var(--tw-text-opacity))}.text-purple-500{color:rgb(168 85 247/var(--tw-text-opacity))}.text-purple-600{color:rgb(147 51 234/var(--tw-text-opacity))}.text-purple-800{color:rgb(107 33 168/var(--tw-text-opacity))}.text-red-500{color:rgb(239 68 68/var(--tw-text-opacity))}.text-red-600{color:rgb(220 38 38/var(--tw-text-opacity))}.text-red-700{color:rgb(185 28 28/var(--tw-text-opacity))}.text-red-800{color:rgb(153 27 27/var(--tw-text-opacity))}.text-white{color:#fff}.text-yellow-500{color:rgb(234 179 8/var(--tw-text-opacity))}.text-yellow-600{color:rgb(202 138 4/var(--tw-text-opacity))}.text-yellow-700{color:rgb(161 98 7/var(--tw-text-opacity))}.text-yellow-800{color:rgb(133 77 14/var(--tw-text-opacity))}.text-moic-accent{color:#e7581c}.text-moic-accent-light{color:#ff6b2c}.text-moic-navy-light{color:#2a1d9e}.underline{text-decoration-line:underline}.opacity-0{opacity:0}.opacity-50{opacity:.5}.opacity-70{opacity:.7}.opacity-80{opacity:.8}.opacity-90{opacity:.9}.shadow-2xl{--tw-shadow:0 25px 50px -12px #00000040;--tw-shadow-colored:0 25px 50px -12px var(--tw-shadow-color);box-shadow:var(--tw-ring-offset-shadow,0 0 #0000),var(--tw-ring-shadow,0 0 #0000),var(--tw-shadow)}.shadow-lg{--tw-shadow:0 10px 15px -3px #0000001a,0 4px 6px -4px #0000001a;--tw-shadow-colored:0 10px 15px -3px var(--tw-shadow-color),0 4px 6px -4px var(--tw-shadow-color);box-shadow:var(--tw-ring-offset-shadow,0 0 #0000),var(--tw-ring-shadow,0 0 #0000),var(--tw-shadow)}.shadow-sm{--tw-shadow:0 1px 2px 0 #0000000d;--tw-shadow-colored:0 1px 2px 0 var(--tw-shadow-color);box-shadow:var(--tw-ring-offset-shadow,0 0 #0000),var(--tw-ring-shadow,0 0 #0000),var(--tw-shadow)}.shadow-xl{--tw-shadow:0 20px 25px -5px #0000001a,0 8px 10px -6px #0000001a;--tw-shadow-colored:0 20px 25px -5px var(--tw-shadow-color),0 8px 10px -6px var(--tw-shadow-color);box-shadow:var(--tw-ring-offset-shadow,0 0 #0000),var(--tw-ring-shadow,0 0 #0000),var(--tw-shadow)}.outline-none{outline:2px solid transparent;outline-offset:2px}.ring-2{--tw-ring-offset-shadow:var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);--tw-ring-shadow:var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color);box-shadow:var(--tw-ring-offset-shadow),var(--tw-ring-shadow),var(--tw-shadow,0 0 #0000)}.ring-red-500{--tw-ring-opacity:1;--tw-ring-color:rgb(239 68 68/var(--tw-ring-opacity))}.ring-offset-2{--tw-ring-offset-width:2px}.blur{--tw-blur:blur(8px);filter:var(--tw-blur) var(--tw-brightness) var(--tw-contrast) var(--tw-grayscale) var(--tw-hue-rotate) var(--tw-invert) var(--tw-saturate) var(--tw-sepia) var(--tw-drop-shadow)}.filter{filter:var(--tw-blur) var(--tw-brightness) var(--tw-contrast) var(--tw-grayscale) var(--tw-hue-rotate) var(--tw-invert) var(--tw-saturate) var(--tw-sepia) var(--tw-drop-shadow)}.transition{transition-property:color,background-color,border-color,text-decoration-color,fill,stroke,opacity,box-shadow,transform,filter,-webkit-backdrop-filter;transition-property:color,background-color,border-color,text-decoration-color,fill,stroke,opacity,box-shadow,transform,filter,backdrop-filter;transition-property:color,background-color,border-color,text-decoration-color,fill,stroke,opacity,box-shadow,transform,filter,backdrop-filter,-webkit-backdrop-filter;transition-timing-function:cubic-bezier(.4,0,.2,1);transition-duration:.15s}.transition-all{transition-property:all;transition-timing-function:cubic-bezier(.4,0,.2,1);transition-duration:.15s}.transition-colors{transition-property:color,background-color,border-color,text-decoration-color,fill,stroke;transition-timing-function:cubic-bezier(.4,0,.2,1);transition-duration:.15s}.transition-opacity{transition-property:opacity;transition-timing-function:cubic-bezier(.4,0,.2,1);transition-duration:.15s}.duration-200{transition-duration:.2s}.duration-300{transition-duration:.3s}.duration-500{transition-duration:.5s}.ease-in-out{transition-timing-function:cubic-bezier(.4,0,.2,1)}.ease-out{transition-timing-function:cubic-bezier(0,0,.2,1)}:root{--moic-navy:#110484;--moic-accent:#e7581c;--moic-gradient:linear-gradient(135deg,#110484,#e7581c)}.card-hover:hover{transform:translateY(-2px);transition:all .2s ease;box-shadow:0 4px 12px #1104841a}.performance-bar{transition:width 1s ease-in-out}.active-tab{border-bottom:3px solid #e7581c!important;font-weight:700!important;color:#fff!important}.tab-badge{position:relative;top:-2px;margin-left:6px}.moic-navy{color:#110484}.moic-navy-light{color:#2a1d9e}.moic-navy-dark{color:#0a0463}.moic-accent{color:#e7581c}.moic-accent-light{color:#ff6b2c}.moic-accent-dark{color:#cc4a15}.bg-moic-navy{background-color:#110484}.bg-moic-navy-light{background-color:#2a1d9e}.bg-moic-navy-dark{background-color:#0a0463}.bg-moic-accent{background-color:#e7581c}.bg-moic-accent-light{background-color:#ff6b2c}.bg-moic-accent-dark{background-color:#cc4a15}.border-moic-navy{border-color:#110484}.border-moic-navy-light{border-color:#2a1d9e}.border-moic-accent{border-color:#e7581c}.border-moic-accent-light{border-color:#ff6b2c}/* Tab text colors - ALL WHITE NOW */
#teamTab span:not(.tab-badge),
#pendingTab span:not(.tab-badge),
#performanceTab span:not(.tab-badge),
#approvedTab span:not(.tab-badge),
#leaveTab span:not(.tab-badge),
#mySupervisorTab span:not(.tab-badge),
#subordinatesTab span:not(.tab-badge) {
    color: white !important;
}

#teamTab.active-tab span:not(.tab-badge),
#pendingTab.active-tab span:not(.tab-badge),
#performanceTab.active-tab span:not(.tab-badge),
#approvedTab.active-tab span:not(.tab-badge),
#leaveTab.active-tab span:not(.tab-badge),
#mySupervisorTab.active-tab span:not(.tab-badge),
#subordinatesTab.active-tab span:not(.tab-badge) {
    color: white !important;
    font-weight: 700 !important;
}

/* Hover states for tabs - ALL WHITE */
#teamTab:hover span:not(.tab-badge),
#pendingTab:hover span:not(.tab-badge),
#performanceTab:hover span:not(.tab-badge),
#approvedTab:hover span:not(.tab-badge),
#leaveTab:hover span:not(.tab-badge),
#mySupervisorTab:hover span:not(.tab-badge),
#subordinatesTab:hover span:not(.tab-badge) {
    color: white !important;
}
        
/* Active tab icon styling */
.active-tab div:first-child {
    background: rgba(255, 255, 255, 0.3) !important;
    border: 1px solid rgba(255, 255, 255, 0.5) !important;
}
        
/* Gradient backgrounds */
.gradient-moic-navy {
    background: linear-gradient(135deg, #0a0463 0%, #110484 50%, #2a1d9e 100%);
}
        
.gradient-moic-accent {
    background: linear-gradient(135deg, #cc4a15 0%, #e7581c 50%, #ff6b2c 100%);
}

.gradient-success {
    background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
}
        
/* Highlight effect for selected member */
.member-highlight {
    border: 2px solid #e7581c !important;
    box-shadow: 0 0 0 3px rgba(231, 88, 28, 0.15) !important;
}
        
/* Modal animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
        
@keyframes slideUp {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
        
.modal-fade-in {
    animation: fadeIn 0.3s ease-out;
}
        
.modal-slide-up {
    animation: slideUp 0.4s ease-out;
}
        
/* Custom scrollbar */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}
        
.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}
        
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #110484;
    border-radius: 10px;
}
        
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #2a1d9e;
}
        
/* Form styling */
.form-input {
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    padding: 10px 16px;
    transition: all 0.3s;
    font-size: 14px;
}
        
.form-input:focus {
    border-color: #e7581c;
    box-shadow: 0 0 0 3px rgba(231, 88, 28, 0.1);
    outline: none;
}
        
.form-select {
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    padding: 10px 16px;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
}
        
.form-select:focus {
    border-color: #e7581c;
    box-shadow: 0 0 0 3px rgba(231, 88, 28, 0.1);
    outline: none;
}
        
/* Button styles */
.btn-primary {
    background: linear-gradient(135deg, #110484 0%, #2a1d9e 100%);
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s;
    border: none;
}
        
.btn-primary:hover {
    background: linear-gradient(135deg, #0a0463 0%, #110484 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(17, 4, 132, 0.2);
}
        
.btn-accent {
    background: linear-gradient(135deg, #e7581c 0%, #ff6b2c 100%);
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s;
    border: none;
}
        
.btn-accent:hover {
    background: linear-gradient(135deg, #cc4a15 0%, #e7581c 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(231, 88, 28, 0.2);
}
        
/* Chart container styling */
.chart-container {
    position: relative;
    height: 250px;
    width: 100%;
}
        
/* Performance rating colors */
.rating-excellent { background-color: #10b981; }
.rating-good { background-color: #3b82f6; }
.rating-fair { background-color: #f59e0b; }
.rating-poor { background-color: #ef4444; }

/* Line clamp for text */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
        
/* Supervisor card special styling */
.supervisor-badge {
    background: linear-gradient(135deg, #e7581c, #ff6b2c);
}

/* Loading spinner */
.loading-spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid rgba(255,255,255,.3);
    border-radius: 50%;
    border-top-color: #fff;
    animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.hover\:scale-105:hover{--tw-scale-x:1.05;--tw-scale-y:1.05;transform:translate(var(--tw-translate-x),var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y))}.hover\:scale-110:hover{--tw-scale-x:1.1;--tw-scale-y:1.1}.hover\:border-transparent:hover{border-color:transparent}.hover\:bg-blue-50:hover{background-color:#eff6ff}.hover\:bg-blue-600:hover{background-color:#2563eb}.hover\:bg-blue-700:hover{background-color:#1d4ed8}.hover\:bg-gray-50:hover{background-color:#f9fafb}.hover\:bg-gray-100:hover{background-color:#f3f4f6}.hover\:bg-gray-200:hover{background-color:#e5e7eb}.hover\:bg-green-50:hover{background-color:#f0fdf4}.hover\:bg-green-600:hover{background-color:#16a34a}.hover\:bg-green-700:hover{background-color:#15803d}.hover\:bg-red-50:hover{background-color:#fef2f2}.hover\:bg-red-700:hover{background-color:#b91c1c}.hover\:bg-white\/20:hover{background-color:#fff3}.hover\:bg-white\/30:hover{background-color:#ffffff4d}.hover\:from-blue-600:hover{--tw-gradient-from:#2563eb var(--tw-gradient-from-position);--tw-gradient-to:#2563eb00 var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-from),var(--tw-gradient-to)}.hover\:from-green-600:hover{--tw-gradient-from:#16a34a var(--tw-gradient-from-position);--tw-gradient-to:#16a34a00 var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-from),var(--tw-gradient-to)}.hover\:from-purple-600:hover{--tw-gradient-from:#9333ea var(--tw-gradient-from-position);--tw-gradient-to:#9333ea00 var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-from),var(--tw-gradient-to)}.hover\:from-red-600:hover{--tw-gradient-from:#dc2626 var(--tw-gradient-from-position);--tw-gradient-to:#dc262600 var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-from),var(--tw-gradient-to)}.hover\:to-blue-700:hover{--tw-gradient-to:#1d4ed8 var(--tw-gradient-to-position)}.hover\:to-green-700:hover{--tw-gradient-to:#15803d var(--tw-gradient-to-position)}.hover\:to-purple-700:hover{--tw-gradient-to:#7e22ce var(--tw-gradient-to-position)}.hover\:to-red-700:hover{--tw-gradient-to:#b91c1c var(--tw-gradient-to-position)}.hover\:text-blue-800:hover{color:#1e40af}.hover\:text-gray-200:hover{color:#e5e7eb}.hover\:text-green-700:hover{color:#15803d}.hover\:text-green-800:hover{color:#166534}.hover\:text-red-800:hover{color:#991b1b}.hover\:text-white:hover{color:#fff}.hover\:text-moic-accent-light:hover{color:#ff6b2c}.hover\:text-moic-navy-light:hover{color:#2a1d9e}.hover\:underline:hover{text-decoration-line:underline}.hover\:shadow-lg:hover{--tw-shadow:0 10px 15px -3px #0000001a,0 4px 6px -4px #0000001a;--tw-shadow-colored:0 10px 15px -3px var(--tw-shadow-color),0 4px 6px -4px var(--tw-shadow-color);box-shadow:var(--tw-ring-offset-shadow,0 0 #0000),var(--tw-ring-shadow,0 0 #0000),var(--tw-shadow)}.hover\:shadow-xl:hover{--tw-shadow:0 20px 25px -5px #0000001a,0 8px 10px -6px #0000001a;--tw-shadow-colored:0 20px 25px -5px var(--tw-shadow-color),0 8px 10px -6px var(--tw-shadow-color)}.focus\:border-transparent:focus{border-color:transparent}.focus\:outline-none:focus{outline:2px solid transparent;outline-offset:2px}.focus\:ring-2:focus{--tw-ring-offset-shadow:var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);--tw-ring-shadow:var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color);box-shadow:var(--tw-ring-offset-shadow),var(--tw-ring-shadow),var(--tw-shadow,0 0 #0000)}.focus\:ring-red-500:focus{--tw-ring-opacity:1;--tw-ring-color:rgb(239 68 68/var(--tw-ring-opacity))}.group:hover .group-hover\:scale-105{--tw-scale-x:1.05;--tw-scale-y:1.05;transform:translate(var(--tw-translate-x),var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y))}.group:hover .group-hover\:scale-110{--tw-scale-x:1.1;--tw-scale-y:1.1}@media (min-width:640px){.sm\:mb-0{margin-bottom:0}.sm\:ml-2{margin-left:.5rem}.sm\:mr-3{margin-right:.75rem}.sm\:mt-0{margin-top:0}.sm\:block{display:block}.sm\:flex{display:flex}.sm\:w-auto{width:auto}.sm\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}.sm\:flex-row{flex-direction:row}.sm\:space-x-4>:not([hidden])~:not([hidden]){--tw-space-x-reverse:0;margin-right:calc(1rem*var(--tw-space-x-reverse));margin-left:calc(1rem*(1 - var(--tw-space-x-reverse)))}.sm\:space-y-0>:not([hidden])~:not([hidden]){--tw-space-y-reverse:0;margin-top:calc(0px*(1 - var(--tw-space-y-reverse)));margin-bottom:calc(0px*var(--tw-space-y-reverse))}.sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}.sm\:text-sm{font-size:.875rem;line-height:1.25rem}}@media (min-width:768px){.md\:mb-0{margin-bottom:0}.md\:flex{display:flex}.md\:w-auto{width:auto}.md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}.md\:grid-cols-3{grid-template-columns:repeat(3,minmax(0,1fr))}.md\:grid-cols-4{grid-template-columns:repeat(4,minmax(0,1fr))}.md\:flex-row{flex-direction:row}.md\:items-center{align-items:center}.md\:gap-6{gap:1.5rem}.md\:px-2{padding-left:.5rem;padding-right:.5rem}.md\:px-8{padding-left:2rem;padding-right:2rem}.md\:py-5{padding-top:1.25rem;padding-bottom:1.25rem}.md\:text-right{text-align:right}.md\:text-base{font-size:1rem;line-height:1.5rem}}@media (min-width:1024px){.lg\:w-2\/5{width:40%}.lg\:w-auto{width:auto}.lg\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}.lg\:grid-cols-4{grid-template-columns:repeat(4,minmax(0,1fr))}.lg\:grid-cols-5{grid-template-columns:repeat(5,minmax(0,1fr))}.lg\:flex-row{flex-direction:row}.lg\:px-8{padding-left:2rem;padding-right:2rem}.lg\:space-y-0>:not([hidden])~:not([hidden]){--tw-space-y-reverse:0;margin-top:calc(0px*(1 - var(--tw-space-y-reverse)));margin-bottom:calc(0px*var(--tw-space-y-reverse))}}@media (min-width:1280px){.xl\:grid-cols-3{grid-template-columns:repeat(3,minmax(0,1fr))}}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Add Chart.js for performance charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --moic-navy: #110484;
            --moic-accent: #e7581c;
            --moic-gradient: linear-gradient(135deg, #110484, #e7581c);
        }
        .card-hover:hover {
            transform: translateY(-2px);
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(17, 4, 132, 0.1);
        }
        .performance-bar {
            transition: width 1s ease-in-out;
        }
        .active-tab {
            border-bottom: 3px solid #e7581c !important;
            font-weight: 700 !important;
            color: white !important;
        }
        .tab-badge {
            position: relative;
            top: -2px;
            margin-left: 6px;
        }
        
        /* Enhanced MOIC Colors */
        .moic-navy { color: #110484; }
        .moic-navy-light { color: #2a1d9e; }
        .moic-navy-dark { color: #0a0463; }
        .moic-accent { color: #e7581c; }
        .moic-accent-light { color: #ff6b2c; }
        .moic-accent-dark { color: #cc4a15; }
        
        .bg-moic-navy { background-color: #110484; }
        .bg-moic-navy-light { background-color: #2a1d9e; }
        .bg-moic-navy-dark { background-color: #0a0463; }
        .bg-moic-accent { background-color: #e7581c; }
        .bg-moic-accent-light { background-color: #ff6b2c; }
        .bg-moic-accent-dark { background-color: #cc4a15; }
        
        .border-moic-navy { border-color: #110484; }
        .border-moic-navy-light { border-color: #2a1d9e; }
        .border-moic-accent { border-color: #e7581c; }
        .border-moic-accent-light { border-color: #ff6b2c; }
        
        /* Tab text colors - ALL WHITE */
        #teamTab span:not(.tab-badge),
        #pendingTab span:not(.tab-badge),
        #performanceTab span:not(.tab-badge),
        #approvedTab span:not(.tab-badge),
        #leaveTab span:not(.tab-badge),
        #mySupervisorTab span:not(.tab-badge),
        #subordinatesTab span:not(.tab-badge) {
            color: white !important;
        }
        
        #teamTab.active-tab span:not(.tab-badge),
        #pendingTab.active-tab span:not(.tab-badge),
        #performanceTab.active-tab span:not(.tab-badge),
        #approvedTab.active-tab span:not(.tab-badge),
        #leaveTab.active-tab span:not(.tab-badge),
        #mySupervisorTab.active-tab span:not(.tab-badge),
        #subordinatesTab.active-tab span:not(.tab-badge) {
            color: white !important;
            font-weight: 700 !important;
        }
        
        /* Hover states for tabs - ALL WHITE */
        #teamTab:hover span:not(.tab-badge),
        #pendingTab:hover span:not(.tab-badge),
        #performanceTab:hover span:not(.tab-badge),
        #approvedTab:hover span:not(.tab-badge),
        #leaveTab:hover span:not(.tab-badge),
        #mySupervisorTab:hover span:not(.tab-badge),
        #subordinatesTab:hover span:not(.tab-badge) {
            color: white !important;
        }
        
        /* Active tab icon styling */
        .active-tab div:first-child {
            background: rgba(255, 255, 255, 0.3) !important;
            border: 1px solid rgba(255, 255, 255, 0.5) !important;
        }
        
        /* Gradient backgrounds */
        .gradient-moic-navy {
            background: linear-gradient(135deg, #0a0463 0%, #110484 50%, #2a1d9e 100%);
        }
        
        .gradient-moic-accent {
            background: linear-gradient(135deg, #cc4a15 0%, #e7581c 50%, #ff6b2c 100%);
        }

        .gradient-success {
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
        }
        
        /* Highlight effect for selected member */
        .member-highlight {
            border: 2px solid #e7581c !important;
            box-shadow: 0 0 0 3px rgba(231, 88, 28, 0.15) !important;
        }
        
        /* Modal animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .modal-fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        
        .modal-slide-up {
            animation: slideUp 0.4s ease-out;
        }
        
        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #110484;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #2a1d9e;
        }
        
        /* Form styling */
        .form-input {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px 16px;
            transition: all 0.3s;
            font-size: 14px;
        }
        
        .form-input:focus {
            border-color: #e7581c;
            box-shadow: 0 0 0 3px rgba(231, 88, 28, 0.1);
            outline: none;
        }
        
        .form-select {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px 16px;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }
        
        .form-select:focus {
            border-color: #e7581c;
            box-shadow: 0 0 0 3px rgba(231, 88, 28, 0.1);
            outline: none;
        }
        
        /* Button styles */
        .btn-primary {
            background: linear-gradient(135deg, #110484 0%, #2a1d9e 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #0a0463 0%, #110484 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(17, 4, 132, 0.2);
        }
        
        .btn-accent {
            background: linear-gradient(135deg, #e7581c 0%, #ff6b2c 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
        }
        
        .btn-accent:hover {
            background: linear-gradient(135deg, #cc4a15 0%, #e7581c 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(231, 88, 28, 0.2);
        }
        
        /* Chart container styling */
        .chart-container {
            position: relative;
            height: 250px;
            width: 100%;
        }
        
        /* Performance rating colors */
        .rating-excellent { background-color: #10b981; }
        .rating-good { background-color: #3b82f6; }
        .rating-fair { background-color: #f59e0b; }
        .rating-poor { background-color: #ef4444; }

        /* Line clamp for text */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Supervisor card special styling */
        .supervisor-badge {
            background: linear-gradient(135deg, #e7581c, #ff6b2c);
        }

        /* Loading spinner */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <!-- Enhanced Header with MOIC Colors -->
    <nav class="gradient-moic-navy shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <!-- MOIC Logo with enhanced styling -->
                    <div class="bg-white p-2.5 rounded-lg shadow-sm">
                        <img class="h-9 w-auto" src="{{ asset('images/moic.png') }}" alt="MOIC Logo">
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-white">Supervisor Portal</h1>
                        <p class="text-sm text-white">Team Management Dashboard</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-white">{{ $supervisor->name }}</p>
                        <p class="text-xs text-white">{{ $supervisor->employee_number }}</p>
                        @if($supervisor->manager)
                            <p class="text-xs text-white mt-1">
                                <i class="fas fa-arrow-up mr-1"></i>Reports to: {{ $supervisor->manager->name }}
                            </p>
                        @endif
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('dashboard') }}" 
                           class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center">
                            <i class="fas fa-exchange-alt mr-2"></i> Switch View
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="bg-white/10 hover:bg-white/20 text-white p-2.5 rounded-lg transition-all duration-200">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
       <!-- Quick Stats with MOIC Colors -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 mb-8">
            <!-- Team Members Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover border-l-4 border-moic-navy">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Team Members</p>
                        <p class="text-3xl font-bold moic-navy mt-1">{{ $stats['team_size'] }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-moic-navy/10 to-moic-navy/20 p-4 rounded-xl">
                        <i class="fas fa-users text-2xl moic-navy"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-user-plus mr-2 text-moic-accent"></i>
                        <span>Managing {{ $stats['team_size'] }} employees</span>
                    </div>
                </div>
            </div>

            <!-- My Supervisor Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover border-l-4 border-moic-navy">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">My Supervisor</p>
                        @if($supervisor->manager)
                            <p class="text-2xl font-bold moic-navy mt-1 truncate">{{ $supervisor->manager->name }}</p>
                        @else
                            <p class="text-lg font-bold text-moic-accent mt-1">Not Assigned</p>
                        @endif
                    </div>
                    <div class="bg-gradient-to-br from-moic-navy/10 to-moic-navy/20 p-4 rounded-xl">
                        <i class="fas fa-user-tie text-2xl moic-navy"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    @if($supervisor->manager)
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-id-badge mr-2 text-moic-accent"></i>
                            <span>{{ $supervisor->manager->employee_number }}</span>
                        </div>
                    @else
                        <div class="flex items-center text-sm text-moic-accent">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span>Select in profile</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Supervisors Reporting Card - NEW -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover border-l-4 border-moic-navy">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Supervisors Reporting</p>
                        <p class="text-3xl font-bold moic-navy mt-1">{{ $subordinateSupervisorsCount ?? 0 }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-moic-navy/10 to-moic-navy/20 p-4 rounded-xl">
                        <i class="fas fa-users-cog text-2xl moic-navy"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-chart-line mr-2 text-moic-accent"></i>
                        <span>{{ $subordinatePendingAppraisals ?? 0 }} pending reviews</span>
                    </div>
                </div>
            </div>

            <!-- Pending Review Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover border-l-4 border-moic-accent">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Pending Review</p>
                        <p class="text-3xl font-bold moic-accent mt-1">{{ $pendingAppraisals->count() }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-moic-accent/10 to-moic-accent/20 p-4 rounded-xl">
                        <i class="fas fa-clock text-2xl moic-accent"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-exclamation-circle mr-2 moic-accent"></i>
                        <span>Action required</span>
                    </div>
                </div>
            </div>

          <!-- Approved Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover border-l-4 border-moic-navy">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-gray-600 text-sm font-medium">Approved</p>
            <p class="text-3xl font-bold moic-navy mt-1">{{ $approvedAppraisals->count() }}</p>
        </div>
        <div class="bg-gradient-to-br from-moic-navy/10 to-moic-navy/20 p-4 rounded-xl">
            <i class="fas fa-check-circle text-2xl moic-navy"></i>
        </div>
    </div>
    <div class="mt-4 pt-4 border-t border-gray-100">
        <div class="flex items-center text-sm text-gray-500">
            <i class="fas fa-history mr-2 moic-navy"></i>
            <span>Completed reviews</span>
        </div>
    </div>
</div>        </div>

        <!-- Main Dashboard Container -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8 overflow-hidden">

            <!-- Responsive Tab Navigation - UPDATED with Subordinates Tab -->
            <div class="gradient-moic-navy">
                <div class="flex gap-2 md:gap-6 px-3 md:px-8 overflow-x-auto custom-scrollbar">

                    <!-- My Team -->
                    <button id="teamTab"
                        class="py-3 md:py-5 px-4 md:px-2 font-semibold active-tab whitespace-nowrap flex flex-col sm:flex-row items-center min-w-[120px] md:min-w-0"
                        onclick="switchTab('team')">

                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center sm:mr-3 mb-1 sm:mb-0">
                            <i class="fas fa-users text-white text-sm"></i>
                        </div>

                        <span class="text-xs sm:text-sm md:text-base text-white">My Team</span>

                        <span class="tab-badge bg-white/30 text-white text-xs px-2 py-0.5 rounded-full sm:ml-2 mt-1 sm:mt-0 font-bold">
                            {{ $stats['team_size'] }}
                        </span>
                    </button>

                    <!-- My Supervisor Tab -->
                    <button id="mySupervisorTab"
                        class="py-3 md:py-5 px-4 md:px-2 font-semibold whitespace-nowrap flex flex-col sm:flex-row items-center min-w-[140px]"
                        onclick="switchTab('mySupervisor')">

                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center sm:mr-3 mb-1 sm:mb-0">
                            <i class="fas fa-user-tie text-white text-sm"></i>
                        </div>

                        <span class="text-xs sm:text-sm md:text-base text-white">My Supervisor</span>

                        @if($supervisor->manager)
                        <span class="tab-badge bg-green-500 text-white text-xs px-2 py-0.5 rounded-full sm:ml-2 mt-1 sm:mt-0 font-bold">
                       
                        </span>
                        @endif
                    </button>

                    <!-- Supervisors Reporting Tab - NEW -->
                    <button id="subordinatesTab"
                        class="py-3 md:py-5 px-4 md:px-2 font-semibold whitespace-nowrap flex flex-col sm:flex-row items-center min-w-[180px]"
                        onclick="switchTab('subordinates')">

                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center sm:mr-3 mb-1 sm:mb-0">
                            <i class="fas fa-users-cog text-white text-sm"></i>
                        </div>

                        <span class="text-xs sm:text-sm md:text-base text-white">Supervisors Reporting</span>

                        @if(isset($subordinateSupervisorsCount) && $subordinateSupervisorsCount > 0)
                        <span class="tab-badge bg-indigo-500 text-white text-xs px-2 py-0.5 rounded-full sm:ml-2 mt-1 sm:mt-0 font-bold">
                            {{ $subordinateSupervisorsCount }}
                        </span>
                        @endif
                    </button>

                    <!-- Pending -->
                    <button id="pendingTab"
                        class="py-3 md:py-5 px-4 md:px-2 font-semibold whitespace-nowrap flex flex-col sm:flex-row items-center min-w-[140px]"
                        onclick="switchTab('pending')">

                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center sm:mr-3 mb-1 sm:mb-0">
                            <i class="fas fa-clock text-white text-sm"></i>
                        </div>

                        <span class="text-xs sm:text-sm md:text-base text-white">Pending Review</span>

                        @if($pendingAppraisals->count() > 0)
                        <span class="tab-badge bg-yellow-500 text-white text-xs px-2 py-0.5 rounded-full sm:ml-2 mt-1 sm:mt-0 font-bold">
                            {{ $pendingAppraisals->count() }}
                        </span>
                        @endif
                    </button>

                    <!-- Performance -->
                    <button id="performanceTab"
                        class="py-3 md:py-5 px-4 md:px-2 font-semibold whitespace-nowrap flex flex-col sm:flex-row items-center min-w-[130px]"
                        onclick="switchTab('performance')">

                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center sm:mr-3 mb-1 sm:mb-0">
                            <i class="fas fa-chart-bar text-white text-sm"></i>
                        </div>

                        <span class="text-xs sm:text-sm md:text-base text-white">Performance</span>
                    </button>

                    <!-- Approved -->
                    <button id="approvedTab"
                        class="py-3 md:py-5 px-4 md:px-2 font-semibold whitespace-nowrap flex flex-col sm:flex-row items-center min-w-[120px]"
                        onclick="switchTab('approved')">

                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center sm:mr-3 mb-1 sm:mb-0">
                            <i class="fas fa-check-circle text-white text-sm"></i>
                        </div>

                        <span class="text-xs sm:text-sm md:text-base text-white">Approved</span>

                        <span class="tab-badge bg-green-500 text-white text-xs px-2 py-0.5 rounded-full sm:ml-2 mt-1 sm:mt-0 font-bold">
                            {{ $approvedAppraisals->count() }}
                        </span>
                    </button>

                    <!-- Leave -->
                    <button id="leaveTab"
                        class="py-3 md:py-5 px-4 md:px-2 font-semibold whitespace-nowrap flex flex-col sm:flex-row items-center min-w-[160px]"
                        onclick="switchTab('leave')">

                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center sm:mr-3 mb-1 sm:mb-0">
                            <i class="fas fa-calendar-alt text-white text-sm"></i>
                        </div>

                        <span class="text-xs sm:text-sm md:text-base text-white">Leave Management</span>

                        @if(isset($leaveStats) && $leaveStats['pending'] > 0)
                        <span class="tab-badge bg-red-500 text-white text-xs px-2 py-0.5 rounded-full sm:ml-2 mt-1 sm:mt-0 font-bold">
                            {{ $leaveStats['pending'] }}
                        </span>
                        @endif
                    </button>
                </div>
            </div>

           <!-- My Supervisor Tab Content -->
            <div id="mySupervisorContent" class="p-8 hidden">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h3 class="text-xl font-bold moic-navy flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-moic-navy/10 to-moic-navy/20 flex items-center justify-center mr-3">
                                <i class="fas fa-user-tie moic-navy"></i>
                            </div>
                            My Supervisor Information
                        </h3>
                        <p class="text-gray-600 mt-2">View and manage your own supervisor relationship</p>
                    </div>
                    <a href="{{ route('profile.show') }}" 
                       class="bg-gradient-to-r from-moic-navy to-moic-navy/90 text-white px-4 py-2.5 rounded-lg text-sm font-semibold hover:shadow transition-all duration-200">
                        <i class="fas fa-edit mr-2"></i> Update in Profile
                    </a>
                </div>

                @if($supervisor->manager)
                    <!-- Supervisor Card -->
                    <div class="bg-gradient-to-br from-white to-moic-navy/5 border border-moic-navy/20 rounded-xl p-8 mb-8">
                        <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                            <div class="flex items-center mb-6 md:mb-0">
                                <div class="w-24 h-24 rounded-2xl gradient-moic-navy flex items-center justify-center mr-6 shadow-lg">
                                    <i class="fas fa-user-tie text-white text-4xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-3xl font-bold moic-navy mb-2">{{ $supervisor->manager->name }}</h2>
                                    <div class="flex flex-wrap gap-3 mb-3">
                                        <span class="bg-gradient-to-r from-moic-navy/10 to-moic-navy/20 text-moic-navy text-sm px-4 py-1.5 rounded-full font-medium">
                                            <i class="fas fa-id-badge mr-2"></i>{{ $supervisor->manager->employee_number }}
                                        </span>
                                        @if($supervisor->manager->job_title)
                                        <span class="bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 text-sm px-4 py-1.5 rounded-full font-medium">
                                            <i class="fas fa-briefcase mr-2"></i>{{ $supervisor->manager->job_title }}
                                        </span>
                                        @endif
                                        @if($supervisor->manager->department)
                                        <span class="bg-gradient-to-r from-moic-accent/10 to-moic-accent/20 text-moic-accent text-sm px-4 py-1.5 rounded-full font-medium">
                                            <i class="fas fa-building mr-2"></i>{{ ucfirst($supervisor->manager->department) }}
                                        </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-envelope mr-2 moic-accent"></i>
                                        <span>{{ $supervisor->manager->email ?? 'No email provided' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col items-end">
                                <div class="bg-white rounded-lg p-4 shadow-sm border border-moic-navy/20">
                                    <p class="text-sm text-gray-600 mb-1">Relationship</p>
                                    <p class="font-bold moic-navy">Direct Supervisor</p>
                                    <p class="text-xs text-gray-500 mt-2">Your appraisals will be sent to this supervisor for review</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Appraisals as Employee -->
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b bg-gradient-to-r from-moic-navy/5 to-moic-navy/10">
                            <h4 class="font-bold moic-navy">My Recent Appraisals (as Employee)</h4>
                            <p class="text-sm text-gray-600">Appraisals submitted to your supervisor</p>
                        </div>
                        
                        @php
                            $myAppraisals = App\Models\Appraisal::where('employee_number', $supervisor->employee_number)
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();
                        @endphp
                        
                        @if($myAppraisals->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Submitted</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($myAppraisals as $appraisal)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $appraisal->period }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold 
                                                @if($appraisal->status == 'approved') bg-green-100 text-green-800
                                                @elseif($appraisal->status == 'submitted') bg-moic-accent/10 text-moic-accent
                                                @elseif($appraisal->status == 'draft') bg-gray-100 text-gray-800
                                                @elseif($appraisal->status == 'rejected') bg-red-100 text-red-800
                                                @else bg-blue-100 text-blue-800 @endif rounded-full">
                                                {{ ucfirst($appraisal->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $appraisal->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('appraisals.show', $appraisal->id) }}" 
                                               class="moic-accent hover:moic-accent-dark text-sm font-medium">
                                                <i class="fas fa-eye mr-1"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-12">
                            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-moic-navy/10 to-moic-navy/20 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-file-alt text-3xl moic-navy"></i>
                            </div>
                            <h4 class="font-bold moic-navy mb-2">No Appraisals Found</h4>
                            <p class="text-gray-500">You haven't submitted any appraisals as an employee yet.</p>
                            <a href="{{ route('appraisals.create') }}" class="mt-4 inline-block bg-gradient-to-r from-moic-navy to-moic-navy/90 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all duration-200">
                                <i class="fas fa-plus mr-2"></i>Create New Appraisal
                            </a>
                        </div>
                        @endif
                    </div>

                @else
                    <!-- No Supervisor Assigned -->
                    <div class="text-center py-16 bg-gradient-to-br from-white to-moic-accent/5 rounded-xl border border-moic-accent/20">
                        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-moic-accent/10 to-moic-accent/20 flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-exclamation-circle text-4xl moic-accent"></i>
                        </div>
                        <h3 class="text-xl font-bold moic-navy mb-2">No Supervisor Assigned</h3>
                        <p class="text-gray-600 mb-6 max-w-md mx-auto">You haven't selected a supervisor for your own appraisals yet. This is needed when you act as an employee.</p>
                        <div class="flex justify-center space-x-4">
                            <a href="{{ route('profile.show') }}" 
                               class="bg-gradient-to-r from-moic-navy to-moic-navy/90 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all duration-200">
                                <i class="fas fa-user-tie mr-2"></i> Select Supervisor in Profile
                            </a>
                            <a href="{{ route('dashboard') }}" 
                               class="bg-white border border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-all duration-200">
                                <i class="fas fa-arrow-left mr-2"></i> Go to Dashboard
                            </a>
                        </div>
                    </div>
                @endif
            </div>

           <!-- Subordinate Supervisors Tab Content - NEW -->
            <div id="subordinatesContent" class="p-8 hidden">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h3 class="text-xl font-bold moic-navy flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-moic-navy/10 to-moic-navy/20 flex items-center justify-center mr-3">
                                <i class="fas fa-users-cog moic-navy"></i>
                            </div>
                            Appraisals from Supervisors Reporting to Me
                        </h3>
                        <p class="text-gray-600 mt-2">Review appraisals submitted by supervisors under your supervision</p>
                    </div>
                    <div class="flex space-x-3">
                        <select id="subordinatePeriodFilter" class="form-select bg-gray-50 text-sm border-moic-navy/20 focus:border-moic-accent focus:ring-moic-accent/20" onchange="filterSubordinateAppraisals()">
                            <option value="">All Periods</option>
                            @foreach($uniquePeriods ?? [] as $period)
                            <option value="{{ $period }}">{{ $period }}</option>
                            @endforeach
                        </select>
                        <select id="subordinateStatusFilter" class="form-select bg-gray-50 text-sm border-moic-navy/20 focus:border-moic-accent focus:ring-moic-accent/20" onchange="filterSubordinateAppraisals()">
                            <option value="">All Status</option>
                            <option value="submitted">Submitted</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                </div>

                <!-- Stats Cards for Subordinate Supervisors -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-gradient-to-br from-white to-moic-navy/5 border border-moic-navy/20 rounded-xl p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-moic-navy/10 to-moic-navy/20 flex items-center justify-center mr-4">
                                <i class="fas fa-user-tie moic-navy text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Supervisors</p>
                                <p class="text-2xl font-bold moic-navy">{{ $subordinateSupervisorsCount ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-white to-blue-50 border border-blue-200 rounded-xl p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center mr-4">
                                <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Total Appraisals</p>
                                <p class="text-2xl font-bold text-blue-700">{{ $subordinateTotalAppraisals ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-white to-moic-accent/5 border border-moic-accent/20 rounded-xl p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-moic-accent/10 to-moic-accent/20 flex items-center justify-center mr-4">
                                <i class="fas fa-clock moic-accent text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Pending Review</p>
                                <p class="text-2xl font-bold moic-accent">{{ $subordinatePendingAppraisals ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Avg Score Card - Updated to MOIC Navy -->
                    <div class="bg-gradient-to-br from-white to-moic-navy/5 border border-moic-navy/20 rounded-xl p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-moic-navy/10 to-moic-navy/20 flex items-center justify-center mr-4">
                                <i class="fas fa-star moic-navy text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Avg Score</p>
                                <p class="text-2xl font-bold moic-navy">{{ number_format($subordinateAvgScore ?? 0, 1) }}%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search and Filter Bar -->
                <div class="flex flex-col lg:flex-row justify-between items-center mb-6 space-y-4 lg:space-y-0">
                    <div class="w-full lg:w-1/3">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" id="subordinateSearch" placeholder="Search by supervisor name or employee number..." 
                                   class="form-input w-full pl-12 pr-4 bg-gray-50 border-moic-navy/20 focus:border-moic-accent focus:ring-moic-accent/20"
                                   onkeyup="filterSubordinateAppraisals()">
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <select id="subordinateSupervisorFilter" class="form-select bg-gray-50 text-sm border-moic-navy/20 focus:border-moic-accent focus:ring-moic-accent/20" onchange="filterSubordinateAppraisals()">
                            <option value="">All Supervisors</option>
                            @foreach($subordinateSupervisors ?? [] as $supervisor)
                            <option value="{{ $supervisor->employee_number }}">{{ $supervisor->name }}</option>
                            @endforeach
                        </select>
                        <button onclick="exportSubordinateAppraisals()" 
                                class="bg-gradient-to-r from-moic-navy to-moic-navy/90 text-white px-4 py-2.5 rounded-lg text-sm font-semibold hover:shadow-lg transition-all duration-200">
                            <i class="fas fa-download mr-2"></i> Export
                        </button>
                    </div>
                </div>

                <!-- Appraisals Table -->
                @if(($subordinateAppraisals ?? collect())->count() > 0)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="gradient-moic-navy">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-user-tie mr-2"></i>Supervisor
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-calendar-alt mr-2"></i>Period
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-info-circle mr-2"></i>Status
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-clock mr-2"></i>Submitted
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-cogs mr-2"></i>Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="subordinateAppraisalsTable">
                                @foreach($subordinateAppraisals as $appraisal)
                                @php
                                    $supervisor = $appraisal->user;
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors duration-150 subordinate-row" 
                                    data-supervisor="{{ strtolower($supervisor->name ?? '') }}"
                                    data-emp-number="{{ strtolower($appraisal->employee_number) }}"
                                    data-period="{{ $appraisal->period }}"
                                    data-status="{{ $appraisal->status }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-gradient-to-br from-moic-navy to-moic-navy/80 flex items-center justify-center mr-3">
                                                <i class="fas fa-user-tie text-white"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold moic-navy">{{ $supervisor->name ?? 'Unknown' }}</div>
                                                <div class="text-xs text-gray-500">{{ $appraisal->employee_number }}</div>
                                                <div class="text-xs text-gray-400 mt-1">{{ $supervisor->job_title ?? 'Supervisor' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $appraisal->period }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1.5 text-xs font-semibold 
                                            @if($appraisal->status == 'approved') bg-green-100 text-green-800
                                            @elseif($appraisal->status == 'submitted') bg-moic-accent/10 moic-accent
                                            @elseif($appraisal->status == 'rejected') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif rounded-full">
                                            @if($appraisal->status == 'submitted')
                                                <i class="fas fa-clock mr-1 moic-accent"></i>
                                            @elseif($appraisal->status == 'approved')
                                                <i class="fas fa-check-circle mr-1 text-green-600"></i>
                                            @elseif($appraisal->status == 'rejected')
                                                <i class="fas fa-times-circle mr-1 text-red-600"></i>
                                            @endif
                                            {{ ucfirst($appraisal->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $appraisal->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $appraisal->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('appraisals.show', $appraisal->id) }}"
                                               class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($appraisal->status == 'submitted')
                                            <a href="{{ route('appraisals.review', $appraisal->id) }}"
                                               class="p-2 moic-accent hover:moic-accent-dark hover:bg-moic-accent/10 rounded-lg transition-colors duration-200"
                                               title="Review Appraisal">
                                                <i class="fas fa-clipboard-check"></i>
                                            </a>
                                            @endif
                                            <button onclick="downloadAppraisal({{ $appraisal->id }})"
                                                    class="p-2 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition-colors duration-200"
                                                    title="Download PDF">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Summary Footer -->
                    <div class="bg-gradient-to-r from-moic-navy/5 to-moic-navy/10 px-6 py-4 border-t border-gray-200">
                        <div class="flex flex-col md:flex-row justify-between items-center">
                            <div class="text-sm text-gray-600 mb-2 md:mb-0">
                                Showing <span id="visibleSubordinateCount">{{ $subordinateAppraisals->count() }}</span> of {{ $subordinateTotalAppraisals ?? 0 }} appraisals
                            </div>
                            <div class="flex items-center space-x-6">
                                <div class="text-sm">
                                    <span class="text-gray-600">Pending:</span>
                                    <span class="font-bold ml-1 moic-accent">{{ $subordinatePendingAppraisals ?? 0 }}</span>
                                </div>
                                <div class="text-sm">
                                    <span class="text-gray-600">Approved:</span>
                                    <span class="font-bold ml-1 text-green-600">{{ $subordinateApprovedAppraisals ?? 0 }}</span>
                                </div>
                                <div class="text-sm">
                                    <span class="text-gray-600">Rejected:</span>
                                    <span class="font-bold ml-1 text-red-600">{{ $subordinateRejectedAppraisals ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-16 bg-gradient-to-br from-white to-moic-navy/5 rounded-xl border border-moic-navy/20">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-moic-navy/10 to-moic-navy/20 flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-file-alt text-4xl moic-navy"></i>
                    </div>
                    <h3 class="text-xl font-bold moic-navy mb-2">No Appraisals Found</h3>
                    <p class="text-gray-600 mb-6 max-w-md mx-auto">There are no appraisals from supervisors reporting to you at this time.</p>
                    @if(($subordinateSupervisorsCount ?? 0) == 0)
                    <div class="bg-moic-accent/5 border border-moic-accent/20 rounded-lg p-4 max-w-md mx-auto">
                        <p class="text-sm moic-accent">
                            <i class="fas fa-info-circle mr-2"></i>
                            You don't have any supervisors reporting to you yet.
                        </p>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            <!-- Team Tab Content -->
            <div id="teamContent" class="p-8">
                <!-- Enhanced Search and Filter Section -->
                <div class="flex flex-col lg:flex-row justify-between items-center mb-8 space-y-4 lg:space-y-0">
                    <div class="w-full lg:w-2/5">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" id="teamSearch" placeholder="Search team members by name or employee number..." 
                                   class="form-input w-full pl-12 pr-4 bg-gray-50"
                                   onkeyup="filterTeam()">
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 w-full lg:w-auto">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-building text-gray-400 text-sm"></i>
                            </div>
                            <select id="departmentFilter" onchange="filterTeam()"
                                    class="form-select pl-10 pr-10 w-full sm:w-auto bg-gray-50">
                                <option value="">All Departments</option>
                                <option value="operations">Operations</option>
                                <option value="finance">Finance</option>
                                <option value="hr">Human Resources</option>
                                <option value="it">IT</option>
                                <option value="admin">Administration</option>
                                <option value="technical">Technical</option>
                                <option value="support">Support</option>
                            </select>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-filter text-gray-400 text-sm"></i>
                            </div>
                            <select id="statusFilter" onchange="filterTeam()"
                                    class="form-select pl-10 pr-10 w-full sm:w-auto bg-gray-50">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="pending">Pending Review</option>
                                <option value="high-performer">High Performer</option>
                                <option value="needs-improvement">Needs Improvement</option>
                                <option value="supervisor">Supervisors Only</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Team Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6" id="teamGrid">
                    @forelse($team as $member)
                    @php
                        // Check if this member is also a supervisor
                        $isSupervisor = $member->user_type === 'supervisor';
                        
                        // Get all approved appraisals for this member
                        $approvedMemberAppraisals = App\Models\Appraisal::with('kpas')
                            ->where('employee_number', $member->employee_number)
                            ->where('status', 'approved')
                            ->orderBy('updated_at', 'desc')
                            ->get();
                        
                        $finalScore = 0;
                        $appraisalCount = $approvedMemberAppraisals->count();
                        $totalScore = 0;
                        
                        if ($appraisalCount > 0) {
                            foreach ($approvedMemberAppraisals as $appraisal) {
                                $score = 0;
                                $totalSupervisorScore = 0;
                                $totalWeight = 0;
                                foreach ($appraisal->kpas as $kpa) {
                                    $rating = $kpa->supervisor_rating ?? $kpa->self_rating;
                                    $totalSupervisorScore += ($rating * $kpa->weight) / 100;
                                    $totalWeight += $kpa->weight;
                                }
                                $score = $totalWeight > 0 ? $totalSupervisorScore : 0;
                                $totalScore += $score;
                            }
                            $finalScore = $totalScore / $appraisalCount;
                        }
                        
                        $hasPending = App\Models\Appraisal::where('employee_number', $member->employee_number)
                            ->where('status', 'submitted')
                            ->exists();
                        
                        // Get pending appraisals for this member
                        $memberPendingAppraisals = App\Models\Appraisal::where('employee_number', $member->employee_number)
                            ->where('status', 'submitted')
                            ->get();
                        
                        // Get member's pending count
                        $memberPendingCount = $memberPendingAppraisals->count();
                        
                        // Get member's supervisor info
                        $memberSupervisor = $member->manager;
                    @endphp
                    <div class="bg-gradient-to-br from-white to-gray-50 border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300 team-member cursor-pointer relative
                        {{ $isSupervisor ? 'border-l-4 border-moic-accent' : '' }}"
                         data-department="{{ strtolower($member->department ?? '') }}"
                         data-status="{{ $hasPending ? 'pending' : ($isSupervisor ? 'supervisor' : 'active') }}"
                         data-employee-number="{{ $member->employee_number }}"
                         onclick="viewMemberDetails('{{ $member->employee_number }}', '{{ $member->name }}')">
                        
                        <!-- Supervisor Badge -->
                        @if($isSupervisor)
                        <div class="absolute top-4 right-4">
                            <span class="supervisor-badge text-white text-xs px-2 py-1 rounded-full flex items-center">
                                <i class="fas fa-user-tie mr-1"></i> Supervisor
                            </span>
                        </div>
                        @endif
                        
                        <!-- Member Header -->
                        <div class="flex items-start justify-between mb-6">
                            <div class="flex items-center space-x-4">
                                <div class="w-14 h-14 rounded-xl {{ $isSupervisor ? 'gradient-moic-accent' : 'gradient-moic-navy' }} flex items-center justify-center">
                                    <i class="fas fa-user text-white text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg moic-navy">{{ $member->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $member->employee_number }}</p>
                                    <div class="flex items-center mt-2 space-x-2 flex-wrap">
                                        <span class="bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 text-xs px-2.5 py-1.5 rounded-full font-medium">
                                            {{ $member->job_title ?? 'Employee' }}
                                        </span>
                                        @if($appraisalCount > 0)
                                        <span class="bg-gradient-to-r from-green-50 to-green-100 text-green-700 text-xs px-2.5 py-1.5 rounded-full font-medium">
                                            <i class="fas fa-check-circle mr-1"></i> {{ $appraisalCount }}
                                        </span>
                                        @endif
                                        @if($hasPending)
                                        <span class="bg-gradient-to-r from-yellow-50 to-yellow-100 text-yellow-700 text-xs px-2.5 py-1.5 rounded-full font-medium">
                                            <i class="fas fa-clock mr-1"></i> Pending
                                        </span>
                                        @endif
                                    </div>
                                    <!-- Show supervisor info if member has one -->
                                    @if($memberSupervisor)
                                    <p class="text-xs text-gray-400 mt-2">
                                        <i class="fas fa-arrow-up mr-1"></i>Reports to: {{ $memberSupervisor->name }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                            <!-- Performance Score -->
                            @if($finalScore > 0)
                            <div class="text-right">
                                <div class="text-2xl font-bold 
                                    @if($finalScore >= 90) text-green-600
                                    @elseif($finalScore >= 70) text-blue-600
                                    @elseif($finalScore >= 50) text-yellow-600
                                    @else text-red-600 @endif">
                                    {{ number_format($finalScore, 1) }}%
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Avg Score</p>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Performance Bar -->
                        @if($finalScore > 0)
                        <div class="mb-6">
                            <div class="flex justify-between text-sm font-medium text-gray-600 mb-2">
                                <span>Performance Rating</span>
                                <span>{{ number_format($finalScore, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="performance-bar h-2.5 rounded-full
                                    @if($finalScore >= 90) bg-green-500
                                    @elseif($finalScore >= 70) bg-blue-500
                                    @elseif($finalScore >= 50) bg-yellow-500
                                    @else bg-red-500 @endif"
                                     style="width: {{ min($finalScore, 100) }}%">
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Action Buttons -->
                        <div class="flex justify-between pt-6 border-t border-gray-200" onclick="event.stopPropagation();">
                            <button onclick="event.stopPropagation(); viewMemberAppraisals('{{ $member->employee_number }}', '{{ $member->name }}')"
                               class="flex items-center text-sm font-semibold moic-navy hover:text-moic-navy-light transition-colors duration-200">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center mr-2">
                                    <i class="fas fa-eye text-blue-600 text-sm"></i>
                                </div>
                                View All
                            </button>
                            @if($hasPending)
                            <button onclick="event.stopPropagation(); viewMemberPending('{{ $member->employee_number }}', '{{ $member->name }}', {{ $memberPendingCount }})"
                               class="flex items-center text-sm font-semibold moic-accent hover:text-moic-accent-light transition-colors duration-200">
                                <div class="w-8 h-8 rounded-lg bg-yellow-50 flex items-center justify-center mr-2">
                                    <i class="fas fa-exclamation-circle text-yellow-600 text-sm"></i>
                                </div>
                                Review Now
                            </button>
                            @endif
                            @if($appraisalCount > 0)
                            <button onclick="event.stopPropagation(); viewMemberApproved('{{ $member->employee_number }}', '{{ $member->name }}', {{ $appraisalCount }})"
                               class="flex items-center text-sm font-semibold text-green-600 hover:text-green-700 transition-colors duration-200">
                                <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center mr-2">
                                    <i class="fas fa-check-circle text-green-600 text-sm"></i>
                                </div>
                                Approved
                            </button>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="col-span-3 text-center py-16">
                        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-users-slash text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="text-xl font-bold moic-navy mb-2">No Team Members Assigned</h3>
                        <p class="text-gray-500 mb-6">You don't have any team members assigned to your supervision yet.</p>
                        <button class="btn-primary">
                            <i class="fas fa-user-plus mr-2"></i> Request Team Members
                        </button>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Pending Tab Content -->
            <div id="pendingContent" class="p-8 hidden">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h3 class="text-xl font-bold moic-navy flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-yellow-50 to-yellow-100 flex items-center justify-center mr-3">
                                <i class="fas fa-clock text-yellow-600"></i>
                            </div>
                            Appraisals Pending Your Review
                        </h3>
                    </div>
                    <span class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white text-sm px-4 py-2 rounded-full font-semibold">
                        <span id="pendingCount">{{ $pendingAppraisals->count() }}</span> waiting
                    </span>
                </div>
                
                <div class="mb-8">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" id="pendingMemberSearch" placeholder="Search by employee name or number..." 
                               class="form-input w-full pl-12 pr-4 bg-gray-50"
                               onkeyup="filterPending()">
                    </div>
                </div>
                
                @if($pendingAppraisals->count() > 0)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="gradient-moic-navy">
                                <tr>
                                    <th scope="col" class="px-8 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-user mr-2"></i>Employee
                                    </th>
                                    <th scope="col" class="px-8 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-calendar-alt mr-2"></i>Period
                                    </th>
                                    <th scope="col" class="px-8 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-chart-bar mr-2"></i>Self Score
                                    </th>
                                    <th scope="col" class="px-8 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-briefcase mr-2"></i>Job Title
                                    </th>
                                    <th scope="col" class="px-8 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-clock mr-2"></i>Submitted
                                    </th>
                                    <th scope="col" class="px-8 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-cogs mr-2"></i>Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="pendingTable">
                                @foreach($pendingAppraisals as $appraisal)
                                <tr class="pending-row hover:bg-gray-50 transition-colors duration-150" 
                                    data-employee="{{ strtolower($appraisal->user->name ?? '') }}"
                                    data-emp-number="{{ strtolower($appraisal->employee_number) }}">
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12 rounded-xl gradient-moic-navy flex items-center justify-center mr-4">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold moic-navy">{{ $appraisal->user->name ?? 'Employee' }}</div>
                                                <div class="text-xs text-gray-500">{{ $appraisal->employee_number }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $appraisal->period }}</div>
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="mr-4">
                                                <span class="text-lg font-bold 
                                                    @if($appraisal->self_score >= 90) text-green-600
                                                    @elseif($appraisal->self_score >= 70) text-blue-600
                                                    @elseif($appraisal->self_score >= 50) text-yellow-600
                                                    @else text-red-600 @endif">
                                                    {{ number_format($appraisal->self_score ?? 0, 1) }}%
                                                </span>
                                            </div>
                                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                                <div class="h-2 rounded-full
                                                    @if($appraisal->self_score >= 90) bg-green-500
                                                    @elseif($appraisal->self_score >= 70) bg-blue-500
                                                    @elseif($appraisal->self_score >= 50) bg-yellow-500
                                                    @else bg-red-500 @endif"
                                                     style="width: {{ min($appraisal->self_score ?? 0, 100) }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <span class="px-3 py-1.5 text-xs font-semibold bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 rounded-full">
                                            {{ $appraisal->user->job_title ?? 'Not specified' }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $appraisal->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $appraisal->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <div class="flex space-x-3">
                                            <a href="{{ route('appraisals.show', $appraisal->id) }}"
                                               class="btn-accent text-sm px-4 py-2">
                                                <i class="fas fa-eye mr-2"></i> Review
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Summary Footer -->
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-4 border-t border-gray-200">
                        <div class="flex flex-col md:flex-row justify-between items-center">
                            <div class="text-sm text-gray-600 mb-2 md:mb-0">
                                Showing <span id="visiblePendingCount" class="font-semibold">{{ $pendingAppraisals->count() }}</span> pending appraisal<span id="pendingPlural">{{ $pendingAppraisals->count() !== 1 ? 's' : '' }}</span>
                            </div>
                            <div class="flex items-center space-x-6">
                                <div class="text-sm">
                                    <span class="text-gray-600">Avg Self Score:</span>
                                    <span class="font-bold ml-1 text-yellow-600">
                                        {{ number_format($pendingAppraisals->avg('self_score') ?? 0, 1) }}%
                                    </span>
                                </div>
                                <div class="text-sm">
                                    <span class="text-gray-600">Earliest:</span>
                                    <span class="font-medium ml-1">
                                        {{ $pendingAppraisals->min('created_at')?->format('M d') ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Stats for Pending -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
                    <div class="bg-gradient-to-br from-white to-yellow-50 border border-yellow-200 rounded-xl p-5 card-hover">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-yellow-100 to-yellow-200 flex items-center justify-center mr-4">
                                <i class="fas fa-user-clock text-yellow-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Oldest Pending</p>
                                <p class="font-bold text-gray-800">
                                    {{ $pendingAppraisals->sortBy('created_at')->first()?->created_at?->diffForHumans() ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-white to-blue-50 border border-blue-200 rounded-xl p-5 card-hover">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center mr-4">
                                <i class="fas fa-users text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Employees Waiting</p>
                                <p class="font-bold text-gray-800">
                                    {{ $pendingAppraisals->pluck('employee_number')->unique()->count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-white to-green-50 border border-green-200 rounded-xl p-5 card-hover">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center mr-4">
                                <i class="fas fa-chart-line text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Avg Score</p>
                                <p class="font-bold text-gray-800">
                                    {{ number_format($pendingAppraisals->avg('self_score') ?? 0, 1) }}%
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="gradient-moic-navy text-white rounded-xl p-5 card-hover">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-white/20 flex items-center justify-center mr-4">
                                <i class="fas fa-tasks text-white"></i>
                            </div>
                            <div>
                                <p class="text-sm text-blue-100">Action Needed</p>
                                <p class="font-bold text-white">Review & Approve Now</p>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-16">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-check-circle text-4xl text-green-500"></i>
                    </div>
                    <h3 class="text-xl font-bold moic-navy mb-2">All Caught Up!</h3>
                    <p class="text-gray-500 mb-6">No pending appraisals to review at the moment.</p>
                    <p class="text-sm text-gray-400">Check back later for new submissions</p>
                </div>
                @endif
            </div>

            <!-- Performance Tab Content -->
            <div id="performanceContent" class="p-8 hidden">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h3 class="text-xl font-bold moic-navy flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-50 to-purple-100 flex items-center justify-center mr-3">
                                <i class="fas fa-chart-bar text-purple-600"></i>
                            </div>
                            Team Performance Analytics
                        </h3>
                    </div>
                    <div class="flex space-x-3">
                        <select id="performancePeriod" class="form-select bg-gray-50" onchange="updateCharts()">
                            <option value="yearly">Yearly</option>
                            <option value="quarterly">Quarterly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                        <select id="performanceMetric" class="form-select bg-gray-50" onchange="updateCharts()">
                            <option value="score">Average Score</option>
                            <option value="completion">Completion Rate</option>
                            <option value="growth">Performance Growth</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Team Performance Overview Chart -->
                    <div class="bg-white border border-gray-200 rounded-xl p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h4 class="font-bold moic-navy">Team Performance Overview</h4>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-500">Current Period</span>
                                <span class="text-sm font-semibold text-purple-600">{{ date('Y') }}</span>
                            </div>
                        </div>
                        <div class="chart-container">
                            <canvas id="performanceChart"></canvas>
                        </div>
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="grid grid-cols-4 gap-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-green-600">{{ number_format($stats['avg_final_score'], 1) }}%</div>
                                    <div class="text-xs text-gray-500">Avg Score</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-600">{{ $approvedAppraisals->count() }}</div>
                                    <div class="text-xs text-gray-500">Completed</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-yellow-600">{{ $pendingAppraisals->count() }}</div>
                                    <div class="text-xs text-gray-500">Pending</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-purple-600">{{ $stats['team_size'] }}</div>
                                    <div class="text-xs text-gray-500">Team Size</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Top Performers Section -->
                    <div class="bg-white border border-gray-200 rounded-xl p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h4 class="font-bold moic-navy">Top Performers</h4>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-500">Ranking</span>
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            @php
                                // Calculate scores for all team members
                                $memberScores = [];
                                foreach($team as $member) {
                                    $approvedMemberAppraisals = App\Models\Appraisal::with('kpas')
                                        ->where('employee_number', $member->employee_number)
                                        ->where('status', 'approved')
                                        ->get();
                                    
                                    $finalScore = 0;
                                    $appraisalCount = $approvedMemberAppraisals->count();
                                    $totalScore = 0;
                                    
                                    if ($appraisalCount > 0) {
                                        foreach ($approvedMemberAppraisals as $appraisal) {
                                            $score = 0;
                                            $totalSupervisorScore = 0;
                                            $totalWeight = 0;
                                            foreach ($appraisal->kpas as $kpa) {
                                                $rating = $kpa->supervisor_rating ?? $kpa->self_rating;
                                                $totalSupervisorScore += ($rating * $kpa->weight) / 100;
                                                $totalWeight += $kpa->weight;
                                            }
                                            $score = $totalWeight > 0 ? $totalSupervisorScore : 0;
                                            $totalScore += $score;
                                        }
                                        $finalScore = $totalScore / $appraisalCount;
                                        
                                        if ($finalScore > 0) {
                                            $memberScores[] = [
                                                'member' => $member,
                                                'score' => $finalScore,
                                                'appraisal_count' => $appraisalCount
                                            ];
                                        }
                                    }
                                }
                                
                                // Sort by score descending and take top 5
                                usort($memberScores, function($a, $b) {
                                    return $b['score'] <=> $a['score'];
                                });
                                $topPerformers = array_slice($memberScores, 0, 5);
                            @endphp
                            
                            @forelse($topPerformers as $index => $data)
                            @php
                                $member = $data['member'];
                                $finalScore = $data['score'];
                                $appraisalCount = $data['appraisal_count'];
                                $isSupervisor = $member->user_type === 'supervisor';
                            @endphp
                            <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-lg transition-colors duration-200 border border-gray-100">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg {{ $isSupervisor ? 'gradient-moic-accent' : 'gradient-moic-navy' }} flex items-center justify-center mr-4 relative">
                                        <i class="fas fa-user text-white"></i>
                                        @if($index < 3)
                                        <div class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-gradient-to-r from-yellow-500 to-yellow-600 flex items-center justify-center text-xs font-bold text-white">
                                            {{ $index + 1 }}
                                        </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $member->name }}</p>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-xs text-gray-500">{{ $member->job_title ?? 'Employee' }}</span>
                                            @if($isSupervisor)
                                            <span class="text-xs bg-orange-100 text-orange-700 px-2 py-0.5 rounded-full">
                                                <i class="fas fa-user-tie mr-1"></i>Supervisor
                                            </span>
                                            @endif
                                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">
                                                {{ $appraisalCount }} appr.
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <div class="w-16 bg-gray-200 rounded-full h-2">
                                            <div class="h-2 rounded-full
                                                @if($finalScore >= 90) bg-green-500
                                                @elseif($finalScore >= 70) bg-blue-500
                                                @elseif($finalScore >= 50) bg-yellow-500
                                                @else bg-red-500 @endif"
                                                 style="width: {{ min($finalScore, 100) }}%">
                                            </div>
                                        </div>
                                        <span class="text-lg font-bold 
                                            @if($finalScore >= 90) text-green-600
                                            @elseif($finalScore >= 70) text-blue-600
                                            @elseif($finalScore >= 50) text-yellow-600
                                            @else text-red-600 @endif">
                                            {{ number_format($finalScore, 1) }}%
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Performance Score</p>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-12">
                                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-chart-line text-3xl text-gray-400"></i>
                                </div>
                                <h4 class="text-lg font-semibold moic-navy mb-2">No Performance Data</h4>
                                <p class="text-gray-500 mb-4">Approve more appraisals to see performance analytics</p>
                                <a href="{{ route('appraisals.index', ['status' => 'submitted']) }}" class="btn-primary text-sm px-4 py-2">
                                    <i class="fas fa-clock mr-2"></i>Review Pending
                                </a>
                            </div>
                            @endforelse
                        </div>
                        
                        @if(count($topPerformers) > 0)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="text-center">
                                    <div class="text-sm text-gray-500">Highest Score</div>
                                    <div class="text-xl font-bold text-green-600">
                                        @if(count($topPerformers) > 0)
                                        {{ number_format(max(array_column($topPerformers, 'score')), 1) }}%
                                        @else
                                        0%
                                        @endif
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="text-sm text-gray-500">Avg Top 5</div>
                                    <div class="text-xl font-bold text-blue-600">
                                        @if(count($topPerformers) > 0)
                                        {{ number_format(array_sum(array_column($topPerformers, 'score')) / count($topPerformers), 1) }}%
                                        @else
                                        0%
                                        @endif
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="text-sm text-gray-500">Team Avg</div>
                                    <div class="text-xl font-bold text-purple-600">{{ number_format($stats['avg_final_score'], 1) }}%</div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>  
            </div>

            <!-- Approved Tab Content -->
            <div id="approvedContent" class="p-8 hidden">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h3 class="text-xl font-bold moic-navy flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center mr-3">
                                <i class="fas fa-check-circle text-green-600"></i>
                            </div>
                            Approved Appraisals
                        </h3>
                    </div>
                    <span class="bg-gradient-to-r from-green-500 to-green-600 text-white text-sm px-4 py-2 rounded-full font-semibold">
                        {{ $approvedAppraisals->count() }} approved
                    </span>
                </div>
                
                <div class="mb-8">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" id="approvedSearch" placeholder="Search approved appraisals..." 
                               class="form-input w-full pl-12 pr-4 bg-gray-50"
                               onkeyup="filterApproved()">
                    </div>
                </div>
                
                @if($approvedAppraisals->count() > 0)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="gradient-moic-navy">
                                <tr>
                                    <th scope="col" class="px-8 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-user mr-2"></i>Employee
                                    </th>
                                    <th scope="col" class="px-8 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-calendar-alt mr-2"></i>Period
                                    </th>
                                   
                                    <th scope="col" class="px-8 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-briefcase mr-2"></i>Job Title
                                    </th>
                                    <th scope="col" class="px-8 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-check mr-2"></i>Approval Date
                                    </th>
                                    <th scope="col" class="px-8 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-cogs mr-2"></i>Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="approvedTable">
                                @foreach($approvedAppraisals as $appraisal)
                                <tr class="approved-row hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12 rounded-xl gradient-moic-navy flex items-center justify-center mr-4">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold moic-navy">{{ $appraisal->user->name ?? 'Employee' }}</div>
                                                <div class="text-xs text-gray-500">{{ $appraisal->employee_number }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $appraisal->period }}</div>
                                    </td>
                                   
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <span class="px-3 py-1.5 text-xs font-semibold bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 rounded-full">
                                            {{ $appraisal->user->job_title ?? 'Not specified' }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $appraisal->updated_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $appraisal->updated_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <div class="flex space-x-3">
                                            <a href="{{ route('appraisals.show', $appraisal->id) }}"
                                               class="btn-primary text-sm px-4 py-2">
                                                <i class="fas fa-eye mr-2"></i> View
                                            </a>
                                            <button onclick="downloadAppraisal({{ $appraisal->id }})"
                                               class="bg-gradient-to-r from-purple-500 to-purple-600 text-white text-sm px-4 py-2 rounded-lg font-semibold hover:from-purple-600 hover:to-purple-700 transition-all duration-200">
                                                <i class="fas fa-download mr-2"></i> PDF
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Summary Footer -->
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-4 border-t border-gray-200">
                        <div class="flex flex-col md:flex-row justify-between items-center">
                            <div class="text-sm text-gray-600 mb-2 md:mb-0">
                                Showing {{ $approvedAppraisals->count() }} approved appraisal{{ $approvedAppraisals->count() !== 1 ? 's' : '' }}
                            </div>
                            
                                <div class="text-sm">
                                    <span class="text-gray-600">Latest Approval:</span>
                                    <span class="font-medium ml-1">
                                        {{ $approvedAppraisals->max('updated_at')?->format('M d') ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                              @else
                <div class="text-center py-16">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-file-alt text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-bold moic-navy mb-2">No Approved Appraisals</h3>
                    <p class="text-gray-500 mb-6">No appraisals have been approved yet.</p>
                    <p class="text-sm text-gray-400">Approve pending reviews to see them here</p>
                </div>
                @endif
            </div>

         <!-- Leave Management Tab Content -->
<div id="leaveContent" class="p-8 hidden">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h3 class="text-xl font-bold" style="color: #110484;">
                <div class="w-10 h-10 rounded-lg" style="background: linear-gradient(135deg, rgba(17,4,132,0.1), rgba(17,4,132,0.2)); display: flex; align-items: center; justify-content: center; margin-right: 0.75rem;">
                    <i class="fas fa-calendar-alt" style="color: #110484;"></i>
                </div>
                Team Leave Management
            </h3>
            <p style="color: #110484; opacity: 0.8; margin-top: 0.5rem;">Review and manage leave requests from your team members</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('supervisor.leaves') }}" 
               style="background: linear-gradient(to right, #110484, #0a0368); color: white; padding: 0.625rem 1rem; border-radius: 0.375rem; font-weight: 500; display: inline-flex; align-items: center; transition: all 0.2s;" 
               onmouseover="this.style.background='linear-gradient(to right, #0a0368, #080255)'" 
               onmouseout="this.style.background='linear-gradient(to right, #110484, #0a0368)'">
                <i class="fas fa-external-link-alt mr-2"></i> Full Leave Management
            </a>
        </div>
    </div>
    
    <!-- Leave Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <!-- Pending Leaves Card -->
        <div class="bg-white rounded-xl shadow-sm border p-6 card-hover border-l-4" style="border-color: #e7581c; border-width: 1px 1px 1px 4px;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: #840804; opacity: 0.9;">Pending Leaves</p>
                    <p class="text-3xl font-bold mt-1" style="color: #e7581c;">{{ $leaveStats['pending'] ?? 0 }}</p>
                </div>
                <div style="background: linear-gradient(135deg, rgba(231, 211, 28, 0.1), rgba(231, 201, 28, 0.2)); padding: 1rem; border-radius: 0.75rem;">
                    <i class="fas fa-clock text-2xl" style="color: #e7581c;"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t" style="border-color: rgba(231,88,28,0.2);">
                <div class="flex items-center text-sm" style="color: #e7581c;">
                    <i class="fas fa-exclamation-circle mr-2" style="color: #e7341c;"></i>
                    <span>Awaiting your approval</span>
                </div>
            </div>
        </div>

        <!-- Approved Leaves Card -->
        <div class="bg-white rounded-xl shadow-sm border p-6 card-hover border-l-4" style="border-color: #04840a; border-width: 1px 1px 1px 4px;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: #048419; opacity: 0.9;">Approved Leaves</p>
                    <p class="text-3xl font-bold mt-1" style="color: #04842f;">{{ $leaveStats['approved'] ?? 0 }}</p>
                </div>
                <div style="background: linear-gradient(135deg, rgba(4, 132, 68, 0.1), rgba(4, 132, 32, 0.2)); padding: 1rem; border-radius: 0.75rem;">
                    <i class="fas fa-check-circle text-2xl" style="color: #04840f;"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t" style="border-color: rgba(4, 132, 79, 0.2);">
                <div class="flex items-center text-sm" style="color: #048435;">
                    <i class="fas fa-calendar-check mr-2" style="color: #04843e;"></i>
                    <span>Approved by you</span>
                </div>
            </div>
        </div>

        <!-- Rejected Leaves Card -->
        <div class="bg-white rounded-xl shadow-sm border p-6 card-hover border-l-4" style="border-color: #e71c1c; border-width: 1px 1px 1px 4px; opacity: 0.7;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: #e71c1c;">Rejected Leaves</p>
                    <p class="text-3xl font-bold mt-1" style="color: #e71c1c;">{{ $leaveStats['rejected'] ?? 0 }}</p>
                </div>
                <div style="background: linear-gradient(135deg, rgba(231, 28, 28, 0.1), rgba(231, 28, 28, 0.2)); padding: 1rem; border-radius: 0.75rem;">
                    <i class="fas fa-times-circle text-2xl" style="color: #e71c1c;"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t" style="border-color: rgba(231, 28, 28, 0.2);">
                <div class="flex items-center text-sm" style="color: #e71c1c;">
                    <i class="fas fa-ban mr-2" style="color: #e71c1c;"></i>
                    <span>Not approved</span>
                </div>
            </div>
        </div>

        <!-- Avg Leave Days Card -->
        <div class="bg-white rounded-xl shadow-sm border p-6 card-hover border-l-4" style="border-color: #110484; border-width: 1px 1px 1px 4px; opacity: 0.8;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: #110484; opacity: 0.9;">Avg Leave Days</p>
                    <p class="text-3xl font-bold mt-1" style="color: #110484;">{{ $leaveStats['avg_days'] ?? 0 }}</p>
                </div>
                <div style="background: linear-gradient(135deg, rgba(17,4,132,0.1), rgba(17,4,132,0.2)); padding: 1rem; border-radius: 0.75rem;">
                    <i class="fas fa-calendar-day text-2xl" style="color: #110484;"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t" style="border-color: rgba(17,4,132,0.2);">
                <div class="flex items-center text-sm" style="color: #110484;">
                    <i class="fas fa-chart-line mr-2" style="color: #110484;"></i>
                    <span>Per approved leave</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Pending Leaves Section -->
       <div class="bg-white rounded-xl border overflow-hidden shadow-sm" style="border-color: rgba(17,4,132,0.3);">
            <div class="px-6 py-4 border-b" style="background: linear-gradient(to right, rgba(17,4,132,0.1), rgba(17,4,132,0.2)); border-bottom-color: rgba(17,4,132,0.3);">
                <div class="flex justify-between items-center">
                    <h4 class="font-bold" style="color: #110484;">Pending Approval</h4>
                    <span style="background: linear-gradient(to right, #110484, #110484); color: white; font-size: 0.75rem; padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 700;">
                        {{ ($pendingLeaves ?? collect())->count() }} requests
                    </span>
                </div>
            </div>
            
            @if(($pendingLeaves ?? collect())->count() > 0)
            <div class="divide-y max-h-96 overflow-y-auto" style="border-color: rgba(28, 58, 231, 0.2);">
                @foreach($pendingLeaves ?? [] as $leave)
                <div class="p-6 transition-colors duration-150" style="hover:background-color: rgba(28, 92, 231, 0.05);" onmouseover="this.style.backgroundColor='rgba(28, 92, 231, 0.05'" onmouseout="this.style.backgroundColor='transparent'">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3" style="background: linear-gradient(135deg, #110484, #110484);">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <div>
                                <div class="font-bold" style="color: #110484;">{{ $leave->employee_name ?? 'Employee' }}</div>
                                <div class="text-sm" style="color: #110484;">{{ $leave->department ?? 'Department' }}</div>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full" 
                            @if($leave->leave_type == 'annual') style="background-color: rgba(17,4,132,0.1); color: #e7581c;"
                            @elseif($leave->leave_type == 'sick') style="background-color: rgba(17,4,132,0.1); color: #e7581c;"
                            @elseif($leave->leave_type == 'maternity') style="background-color: rgba(17,4,132,0.2); color: #e7581c;"
                            @elseif($leave->leave_type == 'paternity') style="background-color: rgba(17,4,132,0.15); color: #e7581c;"
                            @else style="background-color: rgba(17,4,132,0.1); color: #110484;" @endif>
                            {{ $leave->leave_type_name ?? $leave->leave_type ?? 'Leave' }}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <div class="flex items-center text-sm mb-1" style="color: #110484;">
                            <i class="fas fa-calendar-alt mr-2" style="color: #110484;"></i>
                            {{ isset($leave->start_date) ? $leave->start_date->format('M d') : 'N/A' }} - {{ isset($leave->end_date) ? $leave->end_date->format('M d, Y') : 'N/A' }}
                        </div>
                        <div class="flex items-center text-sm" style="color: #148d1e;">
                            <i class="fas fa-clock mr-2" style="color: #110484;"></i>
                            {{ $leave->total_days ?? 0 }} working days
                        </div>
                    </div>
                    
                    <p class="text-sm mb-4 line-clamp-2" style="color: #110484; opacity: 0.7;">{{ $leave->reason ?? 'No reason provided' }}</p>
                    
                    <div class="flex justify-between items-center">
                        <div class="text-xs" style="color: #148d1e;">
                            Applied: {{ isset($leave->created_at) ? $leave->created_at->diffForHumans() : 'N/A' }}
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="viewLeaveDetails({{ $leave->id ?? 0 }})"
                                    style="color: #110484; font-size: 0.875rem; font-weight: 500; background: none; border: none; cursor: pointer;" 
                                    onmouseover="this.style.color='#e7581c'" onmouseout="this.style.color='#110484'">
                                <i class="fas fa-eye mr-1"></i>View
                            </button>
                            <button onclick="approveLeave({{ $leave->id ?? 0 }})"
                                    style="color: #110484; font-size: 0.875rem; font-weight: 500; background: none; border: none; cursor: pointer;" 
                                    onmouseover="this.style.color='rgb(22, 121, 35)'" onmouseout="this.style.color='#110484'">
                                <i class="fas fa-check mr-1"></i>Approve
                            </button>
                            <button onclick="showRejectModal({{ $leave->id ?? 0 }})"
                                    style="color: #e71c1c; font-size: 0.875rem; font-weight: 500; background: none; border: none; cursor: pointer;" 
                                    onmouseover="this.style.color='#e71c1c'" onmouseout="this.style.color='#e71c1c'">
                                <i class="fas fa-times mr-1"></i>Reject
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="px-6 py-4 border-t" style="background-color: rgba(17,4,132,0.1); border-top-color:  rgba(17,4,132,0.2);">
                <a href="{{ route('supervisor.leaves') }}?status=pending" 
                   style="color: #e71c1c; font-size: 0.875rem; font-weight: 500; display: flex; align-items: center; text-decoration: none;" 
                   onmouseover="this.style.color='#e71c1c'" onmouseout="this.style.color='#e71c1c'">
                    View all pending leaves
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            @else
            <div class="text-center py-12">
                <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4" style="background: linear-gradient(135deg, rgba(17,4,132,0.1), rgba(17,4,132,0.2));">
                    <i class="fas fa-check-circle text-3xl" style="color: #110484;"></i>
                </div>
                <h4 class="font-bold mb-2" style="color: #110484;">All Caught Up!</h4>
                <p class="mb-4" style="color: #110484;">No pending leave requests to approve.</p>
                <p class="text-sm" style="color: #110484; opacity: 0.6;">Team members will notify you when they apply for leave</p>
            </div>
            @endif
        </div>
        
        <!-- Upcoming Leaves Section -->
        <div class="bg-white rounded-xl border overflow-hidden shadow-sm" style="border-color: rgba(17,4,132,0.3);">
            <div class="px-6 py-4 border-b" style="background: linear-gradient(to right, rgba(17,4,132,0.1), rgba(17,4,132,0.2)); border-bottom-color: rgba(17,4,132,0.3);">
                <div class="flex justify-between items-center">
                    <h4 class="font-bold" style="color: #110484;">Upcoming Approved Leaves</h4>
                    <span style="background: linear-gradient(to right, #110484, #0a0368); color: white; font-size: 0.75rem; padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 700;">
                        {{ ($upcomingLeaves ?? collect())->count() }} upcoming
                    </span>
                </div>
            </div>
            
            @if(($upcomingLeaves ?? collect())->count() > 0)
            <div class="divide-y max-h-96 overflow-y-auto" style="border-color: rgba(17,4,132,0.2);">
                @foreach($upcomingLeaves ?? [] as $leave)
                <div class="p-6 transition-colors duration-150" style="hover:background-color: rgba(28, 92, 231, 0.05;" onmouseover="this.style.backgroundColor='rgba(28, 92, 231, 0.05'" onmouseout="this.style.backgroundColor='transparent'">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3" style="background: linear-gradient(135deg, #110484, #0a0368);">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <div>
                                <div class="font-bold" style="color: #110484;">{{ $leave->employee_name ?? 'Employee' }}</div>
                                <div class="text-sm" style="color: #110484;">{{ $leave->department ?? 'Department' }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs mb-1" style="color: #e7581c;">Starts in</div>
                            <div class="text-sm font-semibold" style="color: #110484;">
                                {{ isset($leave->start_date) ? $leave->start_date->diffForHumans(['parts' => 2]) : 'N/A' }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="flex items-center text-sm mb-1" style="color: #110484;">
                            <i class="fas fa-calendar-alt mr-2" style="color: #110484;"></i>
                            {{ isset($leave->start_date) ? $leave->start_date->format('M d') : 'N/A' }} - {{ isset($leave->end_date) ? $leave->end_date->format('M d, Y') : 'N/A' }}
                        </div>
                        <div class="flex items-center text-sm" style="color: #148d1e;">
                            <i class="fas fa-clock mr-2" style="color: #110484;"></i>
                            {{ $leave->total_days ?? 0 }} working days
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t" style="border-color: rgba(17,4,132,0.2);">
                        <div class="flex justify-between items-center">
                            <div class="text-xs" style="color: #148d1e;">
                                Approved: {{ isset($leave->approved_at) ? $leave->approved_at->format('M d') : 'N/A' }}
                            </div>
                            <div class="flex space-x-2">
                                <button onclick="viewLeaveDetails({{ $leave->id ?? 0 }})"
                                        style="color: #110484; font-size: 0.875rem; font-weight: 500; background: none; border: none; cursor: pointer;" 
                                        onmouseover="this.style.color='#e7581c'" onmouseout="this.style.color='#110484'">
                                    <i class="fas fa-eye mr-1"></i>View
                                </button>
                                <button onclick="showCancelModal({{ $leave->id ?? 0 }})"
                                        style="color: #e71c1c; font-size: 0.875rem; font-weight: 500; background: none; border: none; cursor: pointer;" 
                                        onmouseover="this.style.color='#840404'" onmouseout="this.style.color='#e71c1c'">
                                    <i class="fas fa-times mr-1"></i>Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="px-6 py-4 border-t" style="background-color: rgba(17,4,132,0.05); border-top-color: rgba(17,4,132,0.3);">
                <a href="{{ route('supervisor.leaves') }}?status=approved" 
                   style="color: #148d1e; font-size: 0.875rem; font-weight: 500; display: flex; align-items: center; text-decoration: none;" 
                   onmouseover="this.style.color='#148d1e'" onmouseout="this.style.color='#148d1e'">
                    View all approved leaves
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            @else
            <div class="text-center py-12">
                <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4" style="background: linear-gradient(135deg, rgba(231,88,28,0.1), rgba(231,88,28,0.2));">
                    <i class="fas fa-calendar-alt text-3xl" style="color: #e7581c;"></i>
                </div>
                <h4 class="font-bold mb-2" style="color: #110484;">No Upcoming Leaves</h4>
                <p class="mb-4" style="color: #e7581c;">No approved leaves scheduled for the near future.</p>
                <p class="text-sm" style="color: #110484; opacity: 0.6;">Team members will appear here when they have approved leaves</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 rounded-xl p-6" style="background: linear-gradient(to right, rgba(17,4,132,0.1), rgba(231,88,28,0.1)); border: 1px solid rgba(17,4,132,0.3);">
        <div class="flex justify-between items-center">
            <div>
                <h4 class="font-bold mb-2" style="color: #110484;">Leave Management Tools</h4>
                <p class="text-sm" style="color: #110484;">Quick actions and settings for managing team leaves</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('supervisor.leaves') }}" 
                   style="background: linear-gradient(to right, #110484, #0a0368); color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; display: inline-flex; align-items: center; text-decoration: none; transition: all 0.2s;" 
                   onmouseover="this.style.background='linear-gradient(to right, #e7581c, #c44a17)'" 
                   onmouseout="this.style.background='linear-gradient(to right, #110484, #0a0368)'">
                    <i class="fas fa-list-alt mr-2"></i>Full Leave List
                </a>
                <button onclick="exportLeaves()"
                        style="background: linear-gradient(to right, #e7581c, #c44a17); color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; display: inline-flex; align-items: center; border: none; cursor: pointer; transition: all 0.2s;" 
                        onmouseover="this.style.background='linear-gradient(to right, #110484, #0a0368)'" 
                        onmouseout="this.style.background='linear-gradient(to right, #e7581c, #c44a17)'">
                    <i class="fas fa-download mr-2"></i>Export Report
                </button>
            </div>
        </div>
    </div>
</div>

        <!-- Enhanced Quick Actions with MOIC Colors -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h4 class="text-xl font-bold moic-navy">Quick Actions</h4>
                    <p class="text-gray-500">Common tasks and shortcuts</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-xs text-gray-500">Last updated: {{ date('h:i A') }}</span>
                    <button onclick="refreshDashboard()" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors duration-200">
                        <i class="fas fa-sync-alt text-gray-600"></i>
                    </button>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- New Appraisal Card -->
                <a href="{{ route('appraisals.create') }}"
                   class="bg-gradient-to-br from-white to-green-50 border border-green-200 rounded-xl p-6 hover:shadow-xl transition-all duration-300 group">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-lg gradient-success flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-plus-circle text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="font-bold text-lg text-green-700">New Appraisal</p>
                            <p class="text-sm text-green-600 opacity-80">Create review</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mt-4">Initiate a new performance evaluation for team members</p>
                </a>
                
                <!-- All Appraisals Card -->
                <a href="{{ route('appraisals.index') }}"
                   class="bg-gradient-to-br from-white to-blue-50 border border-blue-200 rounded-xl p-6 hover:shadow-xl transition-all duration-300 group">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-lg gradient-moic-navy flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-list-alt text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="font-bold text-lg moic-navy">All Appraisals</p>
                            <p class="text-sm moic-navy-light opacity-80">View all reviews</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mt-4">Browse through all completed and ongoing appraisals</p>
                </a>
                
                <!-- Pending Reviews Card -->
                <a href="{{ route('appraisals.index', ['status' => 'submitted']) }}"
                   class="bg-gradient-to-br from-white to-yellow-50 border border-yellow-200 rounded-xl p-6 hover:shadow-xl transition-all duration-300 group">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-r from-yellow-500 to-yellow-600 flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-clock text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="font-bold text-lg text-yellow-700">Pending Reviews</p>
                            <p class="text-sm text-yellow-600 opacity-80">{{ $pendingAppraisals->count() }} waiting</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mt-4">Review and approve submitted performance evaluations</p>
                </a>
                
                <!-- Supervisor's Subordinates Card - NEW -->
                <a href="#" onclick="switchTab('subordinates'); return false;"
                   class="bg-gradient-to-br from-white to-indigo-50 border border-indigo-200 rounded-xl p-6 hover:shadow-xl transition-all duration-300 group">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-users-cog text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="font-bold text-lg text-indigo-700">Supervisors</p>
                            <p class="text-sm text-indigo-600 opacity-80">{{ $subordinateSupervisorsCount ?? 0 }} reporting</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mt-4">View appraisals from supervisors reporting to you</p>
                </a>
            </div>
        </div>

        <!-- Enhanced Footer -->
        <div class="mt-10 pt-8 border-t border-gray-200">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center space-x-4 mb-4 md:mb-0">
                    <div class="bg-white p-2 rounded-lg border border-gray-200">
                        <img class="h-6 w-auto" src="{{ asset('images/moic.png') }}" alt="MOIC Logo">
                    </div>
                    <div>
                        <p class="text-sm font-medium moic-navy">MOIC Performance Management System</p>
                        <p class="text-xs text-gray-500">Version 2.1 • Supervisor Portal</p>
                    </div>
                </div>
                <div class="text-center md:text-right">
                    <p class="text-sm text-gray-600">{{ date('l, F d, Y') }} • {{ date('h:i A') }}</p>
                    <p class="text-xs text-gray-500 mt-1">© {{ date('Y') }} Ministry of Investment &amp; Commerce. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Success Message -->
    @if(session('success'))
    <div id="successMessage" class="fixed bottom-6 right-6 z-50">
        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center modal-slide-up max-w-md">
            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mr-4">
                <i class="fas fa-check text-white"></i>
            </div>
            <div class="flex-1">
                <p class="font-semibold">Success!</p>
                <p class="text-sm opacity-90">{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <script>
        setTimeout(() => {
            const successMsg = document.getElementById('successMessage');
            if (successMsg) {
                successMsg.classList.add('opacity-0');
                successMsg.classList.add('transition-opacity');
                successMsg.classList.add('duration-500');
                setTimeout(() => successMsg.remove(), 500);
            }
        }, 5000);
    </script>
    @endif

    <script>
        // Tab switching functionality - UPDATED to include subordinates
        function switchTab(tab) {
            console.log('Switching to tab:', tab);
            
            // Hide all content
            const tabs = ['team', 'mySupervisor', 'subordinates', 'pending', 'performance', 'approved', 'leave'];
            tabs.forEach(t => {
                const content = document.getElementById(t + 'Content');
                const tabBtn = document.getElementById(t + 'Tab');
                
                if (content) {
                    content.classList.add('hidden');
                }
                if (tabBtn) {
                    tabBtn.classList.remove('active-tab');
                    // Reset text color for non-active tabs
                    const textSpan = tabBtn.querySelector('span:not(.tab-badge)');
                    if (textSpan) {
                        textSpan.style.color = '';
                        textSpan.classList.remove('text-white', 'font-bold');
                    }
                    // Reset icon container background
                    const iconDiv = tabBtn.querySelector('div:first-child');
                    if (iconDiv) {
                        iconDiv.style.background = '';
                        iconDiv.style.border = '';
                    }
                }
            });
            
            // Show selected content and activate tab
            const selectedContent = document.getElementById(tab + 'Content');
            const selectedTab = document.getElementById(tab + 'Tab');
            
            if (selectedContent) {
                selectedContent.classList.remove('hidden');
            }
            if (selectedTab) {
                selectedTab.classList.add('active-tab');
                // Force white text for active tab
                const textSpan = selectedTab.querySelector('span:not(.tab-badge)');
                if (textSpan) {
                    textSpan.style.color = 'white';
                    textSpan.classList.add('text-white', 'font-bold');
                }
                // Highlight icon container for active tab
                const iconDiv = selectedTab.querySelector('div:first-child');
                if (iconDiv) {
                    iconDiv.style.background = 'rgba(255, 255, 255, 0.3)';
                    iconDiv.style.border = '1px solid rgba(255, 255, 255, 0.5)';
                }
            }
            
            // Scroll to top of content
            if (selectedContent) {
                selectedContent.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
            
            // Clear any member-specific filters when switching tabs
            clearMemberFilters();
            
            // Initialize charts when performance tab is selected
            if (tab === 'performance' && !window.chartsInitialized) {
                setTimeout(() => {
                    initializeCharts();
                    window.chartsInitialized = true;
                }, 100);
            }
        }

        // Team filtering functionality
        function filterTeam() {
            const searchTerm = document.getElementById('teamSearch').value.toLowerCase();
            const departmentFilter = document.getElementById('departmentFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            
            const teamMembers = document.querySelectorAll('.team-member');
            
            teamMembers.forEach(member => {
                const name = member.querySelector('h3').textContent.toLowerCase();
                const empNumber = member.querySelector('p.text-sm').textContent.toLowerCase();
                const department = member.getAttribute('data-department');
                const status = member.getAttribute('data-status');
                
                const matchesSearch = name.includes(searchTerm) || empNumber.includes(searchTerm);
                const matchesDepartment = !departmentFilter || department === departmentFilter;
                
                let matchesStatus = true;
                if (statusFilter === 'supervisor') {
                    matchesStatus = status === 'supervisor';
                } else if (statusFilter) {
                    matchesStatus = status === statusFilter;
                }
                
                if (matchesSearch && matchesDepartment && matchesStatus) {
                    member.style.display = 'block';
                } else {
                    member.style.display = 'none';
                }
            });
        }

        // Filter subordinate appraisals - NEW
        function filterSubordinateAppraisals() {
            const searchTerm = document.getElementById('subordinateSearch').value.toLowerCase();
            const supervisorFilter = document.getElementById('subordinateSupervisorFilter').value;
            const periodFilter = document.getElementById('subordinatePeriodFilter').value;
            const statusFilter = document.getElementById('subordinateStatusFilter').value;
            
            const rows = document.querySelectorAll('.subordinate-row');
            let visibleCount = 0;
            
            rows.forEach(row => {
                const supervisorName = row.getAttribute('data-supervisor');
                const empNumber = row.getAttribute('data-emp-number');
                const period = row.getAttribute('data-period');
                const status = row.getAttribute('data-status');
                
                const matchesSearch = supervisorName.includes(searchTerm) || empNumber.includes(searchTerm);
                const matchesSupervisor = !supervisorFilter || empNumber === supervisorFilter.toLowerCase();
                const matchesPeriod = !periodFilter || period === periodFilter;
                const matchesStatus = !statusFilter || status === statusFilter;
                
                if (matchesSearch && matchesSupervisor && matchesPeriod && matchesStatus) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Update visible count
            const countElement = document.getElementById('visibleSubordinateCount');
            if (countElement) {
                countElement.textContent = visibleCount;
            }
        }

        // Filter pending appraisals by member
        function filterPending() {
            const searchTerm = document.getElementById('pendingMemberSearch').value.toLowerCase();
            const rows = document.querySelectorAll('.pending-row');
            let visibleCount = 0;
            
            rows.forEach(row => {
                const employeeName = row.getAttribute('data-employee');
                const empNumber = row.getAttribute('data-emp-number');
                
                const matchesSearch = employeeName.includes(searchTerm) || empNumber.includes(searchTerm);
                
                if (matchesSearch) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Update count display
            const visiblePendingCount = document.getElementById('visiblePendingCount');
            const pendingCount = document.getElementById('pendingCount');
            const pendingPlural = document.getElementById('pendingPlural');
            
            if (visiblePendingCount) visiblePendingCount.textContent = visibleCount;
            if (pendingCount) pendingCount.textContent = visibleCount;
            if (pendingPlural) pendingPlural.textContent = visibleCount !== 1 ? 's' : '';
        }

        // Filter approved appraisals
        function filterApproved() {
            const searchTerm = document.getElementById('approvedSearch').value.toLowerCase();
            const rows = document.querySelectorAll('.approved-row');
            
            rows.forEach(row => {
                const employeeNameElement = row.querySelector('.text-sm.font-bold');
                const empNumberElement = row.querySelector('.text-xs.text-gray-500');
                
                if (!employeeNameElement || !empNumberElement) return;
                
                const employeeName = employeeNameElement.textContent.toLowerCase();
                const empNumber = empNumberElement.textContent.toLowerCase();
                
                const matchesSearch = employeeName.includes(searchTerm) || empNumber.includes(searchTerm);
                
                if (matchesSearch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Export subordinate appraisals - NEW
        function exportSubordinateAppraisals() {
            // Get current filter values
            const supervisorFilter = document.getElementById('subordinateSupervisorFilter').value;
            const periodFilter = document.getElementById('subordinatePeriodFilter').value;
            const statusFilter = document.getElementById('subordinateStatusFilter').value;
            
            // Build export URL with filters
            let url = '{{ route("supervisor.export-subordinate-appraisals") }}?';
            const params = [];
            if (supervisorFilter) params.push(`supervisor=${supervisorFilter}`);
            if (periodFilter) params.push(`period=${periodFilter}`);
            if (statusFilter) params.push(`status=${statusFilter}`);
            
            window.location.href = url + params.join('&');
            
            // Show loading toast
            showToast('info', 'Preparing export...');
        }

        // View member details (click on card)
        function viewMemberDetails(employeeNumber, employeeName) {
            // Remove highlight from all cards first
            const teamMembers = document.querySelectorAll('.team-member');
            teamMembers.forEach(member => {
                member.classList.remove('member-highlight');
                member.style.border = '';
                member.style.boxShadow = '';
            });
            
            // Find and highlight the clicked member's card
            teamMembers.forEach(member => {
                const memberEmpNumber = member.getAttribute('data-employee-number');
                if (memberEmpNumber === employeeNumber) {
                    member.classList.add('member-highlight');
                    
                    // Focus on this member by dimming others
                    teamMembers.forEach(m => {
                        if (m.getAttribute('data-employee-number') !== employeeNumber) {
                            m.style.opacity = '0.4';
                            m.style.transform = 'scale(0.98)';
                        } else {
                            m.style.opacity = '1';
                            m.style.transform = 'scale(1)';
                        }
                    });
                    
                    // Show a modal with detailed information
                    showMemberModal(employeeNumber, employeeName);
                }
            });
        }

        // Show member modal with detailed information
        function showMemberModal(employeeNumber, employeeName) {
            // Create modal overlay
            const modalOverlay = document.createElement('div');
            modalOverlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 modal-fade-in';
            modalOverlay.id = 'memberModal';
            modalOverlay.onclick = function(event) {
                if (event.target === modalOverlay) {
                    closeMemberModal();
                }
            };
            
            // Create modal content
            modalOverlay.innerHTML = `
                <div class="bg-white rounded-xl shadow-xl w-full max-w-4xl max-h-[90vh] overflow-hidden modal-slide-up">
                    <div class="bg-gradient-to-r from-moic-navy to-blue-800 text-white p-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-2xl font-bold">${employeeName}</h2>
                                <p class="opacity-90">${employeeNumber}</p>
                            </div>
                            <button onclick="closeMemberModal()" class="text-white hover:text-gray-200 text-2xl">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 200px)">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <div class="bg-blue-100 p-3 rounded-lg mr-3">
                                        <i class="fas fa-chart-line text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Average Score</p>
                                        <p id="avgScore" class="text-2xl font-bold text-blue-700">Loading...</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-green-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <div class="bg-green-100 p-3 rounded-lg mr-3">
                                        <i class="fas fa-check-circle text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Approved Appraisals</p>
                                        <p id="approvedCount" class="text-2xl font-bold text-green-700">Loading...</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <div class="bg-yellow-100 p-3 rounded-lg mr-3">
                                        <i class="fas fa-clock text-yellow-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Pending Reviews</p>
                                        <p id="pendingCountModal" class="text-2xl font-bold text-yellow-700">Loading...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-t pt-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold moic-navy">Recent Appraisals</h3>
                                <div class="flex space-x-2">
                                    <button onclick="viewMemberAppraisals('${employeeNumber}', '${employeeName}')"
                                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                        <i class="fas fa-list mr-2"></i>View All
                                    </button>
                                    <button onclick="viewMemberPending('${employeeNumber}', '${employeeName}')"
                                            class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 text-sm">
                                        <i class="fas fa-clock mr-2"></i>Pending Reviews
                                    </button>
                                </div>
                            </div>
                            <div id="memberAppraisalsList" class="text-center py-8">
                                <div class="inline-block">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                                    <p class="mt-2 text-gray-500">Loading appraisals...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-t p-4 bg-gray-50 flex justify-end space-x-3">
                        <button onclick="closeMemberModal()"
                                class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                            Close
                        </button>
                        <button onclick="viewMemberAppraisals('${employeeNumber}', '${employeeName}')"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            View All Appraisals
                        </button>
                        <button onclick="window.location.href='{{ route('appraisals.create') }}?employee=${employeeNumber}'"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <i class="fas fa-plus mr-2"></i>New Appraisal
                        </button>
                    </div>
                </div>
            `;
            
            // Add modal to body
            document.body.appendChild(modalOverlay);
            
            // Load member data via AJAX
            loadMemberData(employeeNumber);
            
            // Prevent scrolling on body
            document.body.style.overflow = 'hidden';
        }

        // Close member modal
        function closeMemberModal() {
            const modal = document.getElementById('memberModal');
            if (modal) {
                modal.remove();
            }
            
            // Reset team member display
            const teamMembers = document.querySelectorAll('.team-member');
            teamMembers.forEach(member => {
                member.style.opacity = '1';
                member.style.transform = 'scale(1)';
                member.classList.remove('member-highlight');
            });
            
            // Restore body scrolling
            document.body.style.overflow = 'auto';
        }

        // Load member data via AJAX (mock function - replace with actual API call)
        function loadMemberData(employeeNumber) {
            // Simulate API call delay
            setTimeout(() => {
                // Mock data - replace with actual API call
                const mockData = {
                    avg_score: 87.5,
                    approved_count: 3,
                    pending_count: 1,
                    recent_appraisals: [
                        {
                            id: 1,
                            period: "Q1 2024",
                            status: "approved",
                            score: 88.5,
                            date: "Mar 15, 2024"
                        },
                        {
                            id: 2,
                            period: "Q4 2023",
                            status: "approved",
                            score: 86.0,
                            date: "Dec 20, 2023"
                        },
                        {
                            id: 3,
                            period: "Q3 2023",
                            status: "approved",
                            score: 85.0,
                            date: "Sep 30, 2023"
                        },
                        {
                            id: 4,
                            period: "Q2 2024",
                            status: "submitted",
                            score: 90.0,
                            date: "Jun 10, 2024"
                        }
                    ]
                };
                
                // Update modal with data
                const avgScoreEl = document.getElementById('avgScore');
                const approvedCountEl = document.getElementById('approvedCount');
                const pendingCountModalEl = document.getElementById('pendingCountModal');
                const appraisalsListEl = document.getElementById('memberAppraisalsList');
                
                if (avgScoreEl) avgScoreEl.textContent = `${mockData.avg_score.toFixed(1)}%`;
                if (approvedCountEl) approvedCountEl.textContent = mockData.approved_count;
                if (pendingCountModalEl) pendingCountModalEl.textContent = mockData.pending_count;
                
                // Update appraisals list
                if (appraisalsListEl) {
                    if (mockData.recent_appraisals && mockData.recent_appraisals.length > 0) {
                        let html = '<div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200"><thead class="bg-gray-50"><tr>';
                        html += '<th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period</th>';
                        html += '<th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>';
                        html += '<th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score</th>';
                        html += '<th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>';
                        html += '<th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>';
                        html += '</tr></thead><tbody class="bg-white divide-y divide-gray-200">';
                        
                        mockData.recent_appraisals.forEach(appraisal => {
                            const statusColor = appraisal.status === 'approved' ? 'green' : 
                                               appraisal.status === 'submitted' ? 'yellow' : 'gray';
                            const scoreColor = appraisal.score >= 90 ? 'green' :
                                              appraisal.score >= 70 ? 'blue' :
                                              appraisal.score >= 50 ? 'yellow' : 'red';
                            
                            html += `
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">${appraisal.period}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium bg-${statusColor}-100 text-${statusColor}-800 rounded-full">
                                            ${appraisal.status.charAt(0).toUpperCase() + appraisal.status.slice(1)}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="text-sm font-bold text-${scoreColor}-600 mr-2">${appraisal.score}%</span>
                                            <div class="w-16 bg-gray-200 rounded-full h-1.5">
                                                <div class="bg-${scoreColor}-500 h-1.5 rounded-full" style="width: ${Math.min(appraisal.score, 100)}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">${appraisal.date}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        <a href="/appraisals/${appraisal.id}" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-eye mr-1"></i> View
                                        </a>
                                    </td>
                                </tr>
                            `;
                        });
                        
                        html += '</tbody></table></div>';
                        appraisalsListEl.innerHTML = html;
                    } else {
                        appraisalsListEl.innerHTML = `
                            <div class="text-center py-8">
                                <i class="fas fa-file-alt text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">No appraisals found for this member</p>
                                <button onclick="window.location.href='{{ route('appraisals.create') }}?employee=${employeeNumber}'"
                                        class="mt-4 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                    <i class="fas fa-plus mr-2"></i>Create First Appraisal
                                </button>
                            </div>
                        `;
                    }
                }
            }, 500);
        }

        // View member's appraisals
        function viewMemberAppraisals(employeeNumber, employeeName) {
            window.location.href = `{{ route('appraisals.index') }}?employee=${employeeNumber}`;
        }

        // View member's pending appraisals
        function viewMemberPending(employeeNumber, employeeName, count = null) {
            const url = `{{ route('appraisals.index') }}?employee=${employeeNumber}&status=submitted`;
            window.location.href = url;
        }

        // View member's approved appraisals
        function viewMemberApproved(employeeNumber, employeeName, count = null) {
            const url = `{{ route('appraisals.index') }}?employee=${employeeNumber}&status=approved`;
            window.location.href = url;
        }

        // Clear member-specific filters
        function clearMemberFilters() {
            const teamMembers = document.querySelectorAll('.team-member');
            teamMembers.forEach(member => {
                member.style.display = 'block';
                member.style.opacity = '1';
                member.style.transform = 'scale(1)';
                member.classList.remove('member-highlight');
            });
            
            // Reset search inputs
            const teamSearch = document.getElementById('teamSearch');
            if (teamSearch) teamSearch.value = '';
            const departmentFilter = document.getElementById('departmentFilter');
            if (departmentFilter) departmentFilter.value = '';
            const statusFilter = document.getElementById('statusFilter');
            if (statusFilter) statusFilter.value = '';
            const pendingMemberSearch = document.getElementById('pendingMemberSearch');
            if (pendingMemberSearch) pendingMemberSearch.value = '';
            const approvedSearch = document.getElementById('approvedSearch');
            if (approvedSearch) approvedSearch.value = '';
            
            // Reset subordinate filters if they exist
            const subordinateSearch = document.getElementById('subordinateSearch');
            if (subordinateSearch) subordinateSearch.value = '';
            const subordinateSupervisorFilter = document.getElementById('subordinateSupervisorFilter');
            if (subordinateSupervisorFilter) subordinateSupervisorFilter.value = '';
            const subordinatePeriodFilter = document.getElementById('subordinatePeriodFilter');
            if (subordinatePeriodFilter) subordinatePeriodFilter.value = '';
            const subordinateStatusFilter = document.getElementById('subordinateStatusFilter');
            if (subordinateStatusFilter) subordinateStatusFilter.value = '';
            
            // Reset filter displays
            filterPending();
            filterApproved();
            if (typeof filterSubordinateAppraisals === 'function') {
                filterSubordinateAppraisals();
            }
        }

        // Download appraisal as PDF
        function downloadAppraisal(appraisalId) {
            // Implement PDF download functionality
            console.log('Downloading appraisal:', appraisalId);
            window.open(`/appraisals/${appraisalId}/download`, '_blank');
        }

        // Refresh dashboard
        function refreshDashboard() {
            const refreshBtn = event.target.closest('button');
            refreshBtn.classList.add('animate-spin');
            setTimeout(() => {
                refreshBtn.classList.remove('animate-spin');
                location.reload();
            }, 1000);
        }

        // Chart instances
        let performanceChart = null;
        let trendsChart = null;

        // Initialize performance charts
        function initializeCharts() {
            // Destroy existing charts if they exist
            if (performanceChart) {
                performanceChart.destroy();
            }
            if (trendsChart) {
                trendsChart.destroy();
            }
            
            // Team Performance Overview Chart
            const performanceCtx = document.getElementById('performanceChart').getContext('2d');
            performanceChart = new Chart(performanceCtx, {
                type: 'bar',
                data: {
                    labels: ['Excellent', 'Good', 'Fair', 'Needs Improvement'],
                    datasets: [{
                        label: 'Team Members',
                        data: [35, 45, 15, 5],
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)'
                        ],
                        borderColor: [
                            'rgb(16, 185, 129)',
                            'rgb(59, 130, 246)',
                            'rgb(245, 158, 11)',
                            'rgb(239, 68, 68)'
                        ],
                        borderWidth: 1,
                        borderRadius: 6,
                        barPercentage: 0.6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ${context.raw}%`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            },
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
            
            // Performance Trends Chart
            const trendsCtx = document.getElementById('trendsChart').getContext('2d');
            trendsChart = new Chart(trendsCtx, {
                type: 'line',
                data: {
                    labels: ['Q1 2023', 'Q2 2023', 'Q3 2023', 'Q4 2023', 'Q1 2024', 'Q2 2024'],
                    datasets: [
                        {
                            label: 'Team Average',
                            data: [78, 82, 85, 83, 87, 89],
                            borderColor: 'rgb(16, 185, 129)',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Top Performer',
                            data: [85, 88, 90, 92, 94, 96],
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ${context.raw}%`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            min: 70,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            },
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                drawBorder: false
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'nearest'
                    }
                }
            });
        }

        // Update charts based on filters
        function updateCharts() {
            const period = document.getElementById('performancePeriod').value;
            const metric = document.getElementById('performanceMetric').value;
            
            // Update chart data based on selections
            if (performanceChart && trendsChart) {
                // Simulate data changes based on selections
                let performanceData, trendLabels, trendData1, trendData2;
                
                switch(period) {
                    case 'monthly':
                        trendLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
                        break;
                    case 'quarterly':
                        trendLabels = ['Q1', 'Q2', 'Q3', 'Q4'];
                        break;
                    case 'yearly':
                    default:
                        trendLabels = ['2020', '2021', '2022', '2023', '2024'];
                        break;
                }
                
                switch(metric) {
                    case 'completion':
                        performanceData = [40, 50, 8, 2];
                        trendData1 = [65, 70, 75, 80, 85, 90];
                        trendData2 = [75, 80, 85, 90, 95, 98];
                        break;
                    case 'growth':
                        performanceData = [30, 40, 20, 10];
                        trendData1 = [70, 75, 78, 82, 85, 88];
                        trendData2 = [80, 83, 86, 89, 92, 95];
                        break;
                    case 'score':
                    default:
                        performanceData = [35, 45, 15, 5];
                        trendData1 = [78, 82, 85, 83, 87, 89];
                        trendData2 = [85, 88, 90, 92, 94, 96];
                        break;
                }
                
                // Update performance chart
                performanceChart.data.datasets[0].data = performanceData;
                performanceChart.update();
                
                // Update trends chart
                trendsChart.data.labels = trendLabels.slice(-6); // Last 6 periods
                trendsChart.data.datasets[0].data = trendData1.slice(-6);
                trendsChart.data.datasets[1].data = trendData2.slice(-6);
                trendsChart.update();
            }
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeMemberModal();
            }
        });

        // Animate performance bars on load
        document.addEventListener('DOMContentLoaded', function() {
            const performanceBars = document.querySelectorAll('.performance-bar');
            performanceBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0';
                setTimeout(() => {
                    bar.style.width = width;
                }, 100);
            });
            
            // Set initial active tab
            switchTab('team');
            
            // Add smooth scrolling for tab content
            const tabButtons = document.querySelectorAll('[onclick^="switchTab"]');
            tabButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const tabName = this.getAttribute('onclick').match(/'([^']+)'/)[1];
                    switchTab(tabName);
                });
            });
            
            // Initialize charts flag
            window.chartsInitialized = false;
        });

        // View leave details
        function viewLeaveDetails(leaveId) {
            window.open(`/leaves/${leaveId}`, '_blank');
        }

        // Approve leave function
        function approveLeave(leaveId) {
            if (confirm('Are you sure you want to approve this leave request?')) {
                fetch(`/supervisor/leaves/${leaveId}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('success', data.message);
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showToast('error', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'An error occurred while approving the leave.');
                });
            }
        }

        // Show reject modal
        function showRejectModal(leaveId) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
            modal.id = 'rejectModal';
            
            modal.innerHTML = `
                <div class="bg-white rounded-xl shadow-xl w-full max-w-md modal-slide-up">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-red-600 mb-2">Reject Leave Request</h3>
                        <p class="text-gray-600 mb-4">Please provide a reason for rejecting this leave request.</p>
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-medium mb-2">Reason for Rejection</label>
                            <textarea id="rejectReason" rows="4" 
                                      class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                      placeholder="Enter the reason for rejecting this leave request..."
                                      required></textarea>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button onclick="document.getElementById('rejectModal').remove()"
                                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50">
                                Cancel
                            </button>
                            <button onclick="rejectLeave(${leaveId})"
                                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                Reject Leave
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
        }

        // Reject leave function
        function rejectLeave(leaveId) {
            const reason = document.getElementById('rejectReason').value;
            
            if (!reason.trim()) {
                alert('Please provide a reason for rejection.');
                return;
            }
            
            fetch(`/supervisor/leaves/${leaveId}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ remarks: reason })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('success', data.message);
                    document.getElementById('rejectModal').remove();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'An error occurred while rejecting the leave.');
            });
        }

        // Show cancel modal
        function showCancelModal(leaveId) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
            modal.id = 'cancelModal';
            
            modal.innerHTML = `
                <div class="bg-white rounded-xl shadow-xl w-full max-w-md modal-slide-up">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-red-600 mb-2">Cancel Approved Leave</h3>
                        <p class="text-gray-600 mb-4">Are you sure you want to cancel this approved leave? This action cannot be undone.</p>
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-medium mb-2">Reason for Cancellation</label>
                            <textarea id="cancelReason" rows="4" 
                                      class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                      placeholder="Enter the reason for cancelling this leave..."
                                      required></textarea>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button onclick="document.getElementById('cancelModal').remove()"
                                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50">
                                Keep Leave
                            </button>
                            <button onclick="cancelLeave(${leaveId})"
                                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                Cancel Leave
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
        }

        // Cancel leave function
        function cancelLeave(leaveId) {
            const reason = document.getElementById('cancelReason').value;
            
            if (!reason.trim()) {
                alert('Please provide a reason for cancellation.');
                return;
            }
            
            fetch(`/supervisor/leaves/${leaveId}/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ remarks: reason })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('success', data.message);
                    document.getElementById('cancelModal').remove();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'An error occurred while cancelling the leave.');
            });
        }

        // Export leaves function
        function exportLeaves() {
            showToast('info', 'Export feature coming soon!');
        }

        // Toast notification function
        function showToast(type, message) {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-xl text-white flex items-center modal-slide-up ${type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'}`;
            
            toast.innerHTML = `
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center mr-4">
                    <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'info-circle'} text-white"></i>
                </div>
                <div>
                    <p class="font-semibold">${type === 'success' ? 'Success!' : type === 'error' ? 'Error!' : 'Info'}</p>
                    <p class="text-sm opacity-90">${message}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="ml-6 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                setTimeout(() => toast.remove(), 500);
            }, 5000);
        }
    </script>
</body>
</html>