<x-form :action="route('staff.reminders.update', $reminder)" method="PATCH" has-files>
    <div class="flex space-x-2">
        <x-input.file id="attachments" name="attachments[]" accept="application/pdf, image/*" class="flex-1 form-input overflow-hidden"
            placeholder="Choose maximum 2 PDF or Scanned image file(s)" :multiple="true">
        </x-input.file>
        <button class="btn btn-magenta is-sm">Submit</button>
    </div>
</x-form>
