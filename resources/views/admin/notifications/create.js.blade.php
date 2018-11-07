$("#notifications").append(@json(view("workflow::admin.notifications._form", compact('form'))->render()));
$("#notifications .select2").select2();
