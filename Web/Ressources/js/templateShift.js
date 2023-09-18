/*
let scheduleData = [{
Id: 1,
Subject: 'Explosion of Betelgeuse Star',
StartTime: new Date(2018, 1, 15, 9, 30),
EndTime: new Date(2018, 1, 15, 11, 0)
}, {
Id: 2,
Subject: 'Thule Air Crash Report',
StartTime: new Date(2018, 1, 12, 12, 0),
EndTime: new Date(2018, 1, 12, 14, 0)
}, {
Id: 3,
Subject: 'Blue Moon Eclipse',
StartTime: new Date(2018, 1, 13, 9, 30),
EndTime: new Date(2018, 1, 13, 11, 0)
}, {
Id: 4,
Subject: 'Meteor Showers in 2018',
StartTime: new Date(2018, 1, 14, 13, 0),
EndTime: new Date(2018, 1, 14, 14, 30)
}];


*/


var scheduleData = [
    {
        RoomId: 10,
        Id: 1,
        Subject: "Board Meeting",
        Description: "Meeting to discuss business goal of 2020.",
        StartTime: "2020-01-05T04:00:00.000Z",
        EndTime: "2020-01-05T05:30:00.000Z"
    },
    {
        RoomId: 8,
        Id: 2,
        Subject: "Training session on JSP",
        Description: "Knowledge sharing on JSP topics.",
        StartTime: "2020-01-07T04:00:00.000Z",
        EndTime: "2020-01-07T05:30:00.000Z"
    },
    {
        RoomId: 3,
        Id: 3,
        Subject: "Sprint Planning with Team members",
        Description: "Planning tasks for sprint.",
        StartTime: "2020-01-09T04:00:00.000Z",
        EndTime: "2020-01-09T05:30:00.000Z"
    },
     {
         RoomId: 2,
         Id: 4,
         Subject: "Meeting with Client",
         Description: "Customer meeting to discuss features.",
         StartTime: "2020-01-11T03:30:00.000Z",
         EndTime: "2020-01-11T05:00:00.000Z"
     },
     {
         RoomId: 5,
         Id: 5,
         Subject: "Support Meeting with Managers",
         Description: "Meeting to discuss support plan.",
         StartTime: "2020-01-06T06:30:00.000Z",
         EndTime: "2020-01-06T08:00:00.000Z"
     },
     {
         RoomId: 1,
         Id: 6,
         Subject: "Client Meeting",
         Description: "Meeting to discuss client requirements.",
         StartTime: "2020-01-08T06:00:00.000Z",
         EndTime: "2020-01-08T07:30:00.000Z"
     },
     {
         RoomId: 7,
         Id: 7,
         Subject: "Appraisal Meeting",
         Description: "Meeting to discuss employee appraisals.",
         StartTime: "2020-01-10T05:30:00.000Z",
         EndTime: "2020-01-10T07:00:00.000Z"
     },
     {
         RoomId: 6,
         Id: 8,
         Subject: "HR Meeting",
         Description: "Meeting to discuss HR plans.",
         StartTime: "2020-01-05T07:30:00.000Z",
         EndTime: "2020-01-05T09:00:00.000Z"
     },
     {
         RoomId: 4,
         Id: 9,
         Subject: "Customer Meeting",
         Description: "Meeting to discuss customer reported issues.",
         StartTime: "2020-01-09T07:00:00.000Z",
         EndTime: "2020-01-09T08:30:00.000Z"
     },
     {
         RoomId: 9,
         Id: 10,
         Subject: "Board Meeting",
         Description: "Meeting to discuss business plans.",
         StartTime: "2020-01-11T07:30:00.000Z",
         EndTime: "2020-01-11T09:00:00.000Z"
     },
     {
        RoomId: 10,
        Id: 11,
        Subject: "Board Meeting",
        Description: "Meeting to discuss business goal of 2020.",
        StartTime: "2020-01-05T04:00:00.000Z",
        EndTime: "2020-01-05T05:30:00.000Z"
    },
    {
        RoomId: 8,
        Id: 12,
        Subject: "Training session on JSP",
        Description: "Knowledge sharing on JSP topics.",
        StartTime: "2020-01-07T04:00:00.000Z",
        EndTime: "2020-01-07T05:30:00.000Z"
    },
    {
        RoomId: 3,
        Id: 13,
        Subject: "Sprint Planning with Team members",
        Description: "Planning tasks for sprint.",
        StartTime: "2020-01-09T04:00:00.000Z",
        EndTime: "2020-01-09T05:30:00.000Z"
    },
     {
         RoomId: 2,
         Id: 14,
         Subject: "Meeting with Client",
         Description: "Customer meeting to discuss features.",
         StartTime: "2020-01-11T03:30:00.000Z",
         EndTime: "2020-01-11T05:00:00.000Z"
     },
     {
         RoomId: 5,
         Id: 15,
         Subject: "Support Meeting with Managers",
         Description: "Meeting to discuss support plan.",
         StartTime: "2020-01-06T06:30:00.000Z",
         EndTime: "2020-01-06T08:00:00.000Z"
     },
     {
         RoomId: 1,
         Id: 16,
         Subject: "Client Meeting",
         Description: "Meeting to discuss client requirements.",
         StartTime: "2020-01-08T06:00:00.000Z",
         EndTime: "2020-01-08T07:30:00.000Z"
     },
     {
         RoomId: 7,
         Id: 17,
         Subject: "Appraisal Meeting",
         Description: "Meeting to discuss employee appraisals.",
         StartTime: "2020-01-10T05:30:00.000Z",
         EndTime: "2020-01-10T07:00:00.000Z"
     },
     {
         RoomId: 6,
         Id: 18,
         Subject: "HR Meeting",
         Description: "Meeting to discuss HR plans.",
         StartTime: "2020-01-05T07:30:00.000Z",
         EndTime: "2020-01-05T09:00:00.000Z"
     },
     {
         RoomId: 4,
         Id: 19,
         Subject: "Customer Meeting",
         Description: "Meeting to discuss customer reported issues.",
         StartTime: "2020-01-09T07:00:00.000Z",
         EndTime: "2020-01-09T08:30:00.000Z"
     },
     {
         RoomId: 9,
         Id: 20,
         Subject: "Board Meeting",
         Description: "Meeting to discuss business plans.",
         StartTime: "2020-01-11T07:30:00.000Z",
         EndTime: "2020-01-11T09:00:00.000Z"
     },
     {
         RoomId: 9,
         Id: 21,
         Subject: "Board Meeting",
         Description: "Meeting to discuss business plans.",
         StartTime: "2020-01-11T07:30:00.000Z",
         EndTime: "2020-01-11T09:00:00.000Z"
     },
     {
         RoomId: 9,
         Id: 22,
         Subject: "Board Meeting",
         Description: "Meeting to discuss business plans.",
         StartTime: "2020-01-11T07:30:00.000Z",
         EndTime: "2020-01-11T09:00:00.000Z"
     },
     {
         RoomId: 9,
         Id: 23,
         Subject: "Board Meeting",
         Description: "Meeting to discuss business plans.",
         StartTime: "2020-01-11T07:30:00.000Z",
         EndTime: "2020-01-11T09:00:00.000Z"
     },
     {
         RoomId: 9,
         Id: 24,
         Subject: "Board Meeting",
         Description: "Meeting to discuss business plans.",
         StartTime: "2020-01-11T07:30:00.000Z",
         EndTime: "2020-01-11T09:00:00.000Z"
     },
     {
         RoomId: 9,
         Id: 25,
         Subject: "Board Meeting",
         Description: "Meeting to discuss business plans.",
         StartTime: "2020-01-11T07:30:00.000Z",
         EndTime: "2020-01-11T09:00:00.000Z"
     },
     {
         RoomId: 9,
         Id: 26,
         Subject: "Board Meeting",
         Description: "Meeting to discuss business plans.",
         StartTime: "2020-01-11T07:30:00.000Z",
         EndTime: "2020-01-11T09:00:00.000Z"
     },
     {
         RoomId: 9,
         Id: 27,
         Subject: "Board Meeting",
         Description: "Meeting to discuss business plans.",
         StartTime: "2020-01-11T07:30:00.000Z",
         EndTime: "2020-01-11T09:00:00.000Z"
     },
     {
         RoomId: 9,
         Id: 28,
         Subject: "Board Meeting",
         Description: "Meeting to discuss business plans.",
         StartTime: "2020-01-11T07:30:00.000Z",
         EndTime: "2020-01-11T09:00:00.000Z"
     },
     {
         RoomId: 9,
         Id: 29,
         Subject: "Board Meeting",
         Description: "Meeting to discuss business plans.",
         StartTime: "2020-01-11T07:30:00.000Z",
         EndTime: "2020-01-11T09:00:00.000Z"
     },
     {
         RoomId: 9,
         Id: 30,
         Subject: "Board Meeting",
         Description: "Meeting to discuss business plans.",
         StartTime: "2020-01-11T07:30:00.000Z",
         EndTime: "2020-01-11T09:00:00.000Z"
     },
     {
         RoomId: 9,
         Id: 31,
         Subject: "Board Meeting",
         Description: "Meeting to discuss business plans.",
         StartTime: "2020-01-11T07:30:00.000Z",
         EndTime: "2020-01-11T09:00:00.000Z"
     },
     {
         RoomId: 9,
         Id: 32,
         Subject: "Board Meeting",
         Description: "Meeting to discuss business plans.",
         StartTime: "2020-01-11T07:30:00.000Z",
         EndTime: "2020-01-11T09:00:00.000Z"
     },
     {
         RoomId: 9,
         Id: 33,
         Subject: "Board Meeting",
         Description: "Meeting to discuss business plans.",
         StartTime: "2020-01-11T07:30:00.000Z",
         EndTime: "2020-01-11T09:00:00.000Z"
     },
     {
         RoomId: 9,
         Id: 34,
         Subject: "Board Meeting",
         Description: "Meeting to discuss business plans.",
         StartTime: "2020-01-11T07:30:00.000Z",
         EndTime: "2020-01-11T09:00:00.000Z"
     },
     {
         RoomId: 9,
         Id: 35,
         Subject: "Board Meeting",
         Description: "Meeting to discuss business plans.",
         StartTime: "2020-01-11T07:30:00.000Z",
         EndTime: "2020-01-11T09:00:00.000Z"
     },
     {
         RoomId: 9,
         Id: 36,
         Subject: "Board Meeting",
         Description: "Meeting to discuss business plans.",
         StartTime: "2020-01-11T07:30:00.000Z",
         EndTime: "2020-01-11T09:00:00.000Z"
     },
     {
         RoomId: 9,
         Id: 37,
         Subject: "Board Meeting",
         Description: "Meeting to discuss business plans.",
         StartTime: "2020-01-11T07:30:00.000Z",
         EndTime: "2020-01-11T09:00:00.000Z"
     },
     {
         RoomId: 9,
         Id: 38,
         Subject: "Board Meeting",
         Description: "Meeting to discuss business plans.",
         StartTime: "2020-01-11T07:30:00.000Z",
         EndTime: "2020-01-11T09:00:00.000Z"
     },
     {
         RoomId: 9,
         Id: 39,    
         Subject: "Board Meeting",
         Description: "Meeting to discuss business plans.",
         StartTime: "2020-01-11T07:30:00.000Z",
         EndTime: "2020-01-11T09:00:00.000Z"
     },
     {
         RoomId: 9,
         Id: 40,
         Subject: "Board Meeting",
         Description: "Meeting to discuss business plans.",
         StartTime: "2020-01-11T07:30:00.000Z",
         EndTime: "2020-01-11T09:00:00.000Z"
     },
     {
         RoomId: 9,
         Id: 41,
         Subject: "Board Meeting",
         Description: "Meeting to discuss business plans.",
         StartTime: "2020-01-11T07:30:00.000Z",
         EndTime: "2020-01-11T09:00:00.000Z"
     },
     {
         RoomId: 9,
         Id: 42,
         Subject: "Board Meeting",
         Description: "Meeting to discuss business plans.",
         StartTime: "2020-01-11T07:30:00.000Z",
         EndTime: "2020-01-11T09:00:00.000Z"
     },
     {
         RoomId: 9,
         Id: 43,
         Subject: "Board Meeting",
         Description: "Meeting to discuss business plans.",
         StartTime: "2020-01-11T07:30:00.000Z",
         EndTime: "2020-01-11T09:00:00.000Z"
     },
     {
         RoomId: 9,
         Id: 44,
         Subject: "Board Meeting",
         Description: "Meeting to discuss business plans.",
         StartTime: "2020-01-11T07:30:00.000Z",
         EndTime: "2020-01-11T09:00:00.000Z"
     },
     {
         RoomId: 9,
         Id: 45,
         Subject: "Board Meeting",
         Description: "Meeting to discuss business plans.",
         StartTime: "2020-01-11T07:30:00.000Z",
         EndTime: "2020-01-11T09:00:00.000Z"
     },
     {
         RoomId: 9,
         Id: 46,
         Subject: "Board Meeting",
         Description: "Meeting to discuss business plans.",
         StartTime: "2020-01-11T07:30:00.000Z",
         EndTime: "2020-01-11T09:00:00.000Z"
     },
     {
         RoomId: 9,
         Id: 47,
         Subject: "Board Meeting",
         Description: "Meeting to discuss business plans.",
         StartTime: "2020-01-11T07:30:00.000Z",
         EndTime: "2020-01-11T09:00:00.000Z"
     }
];


    window.getResourceData = function (data) {
        var resources = scheduleObj.getResourceCollections().slice(-1)[0];
        var resourceData = resources.dataSource.filter(function (resource) { return resource.Id === data.RoomId; })[0];
        return resourceData;
    };

    window.getHeaderDetails = function (data) {
        var intl = new ej.base.Internationalization();
        return intl.formatDate(data.StartTime, { type: 'date', skeleton: 'full' }) + ' (' +
            intl.formatDate(data.StartTime, { skeleton: 'hm' }) + ' - ' + intl.formatDate(data.EndTime, { skeleton: 'hm' }) + ')';
    };

    window.getHeaderStyles = function (data) {
        if (data.elementType === 'cell') {
            return 'align-items: center; color: #919191;';
        } else {
            var resourceData = window.getResourceData(data);
            return 'background:' + resourceData.Color + '; color: #FFFFFF;';
        }
    };

    window.getEventType = function (data) {
        var resourceData = window.getResourceData(data);
        return resourceData.Name;
    };

    var buttonClickActions = function (e) {
        var quickPopup = scheduleObj.element.querySelector('.e-quick-popup-wrapper');
        var getSlotData = function () {
            var cellDetails = scheduleObj.getCellDetails(scheduleObj.getSelectedElements());
            if (ej.base.isNullOrUndefined(cellDetails)) {
                cellDetails = scheduleObj.getCellDetails(scheduleObj.activeCellsData.element);
            }
            var subject = quickPopup.querySelector('#title').ej2_instances[0].value;
            var notes = quickPopup.querySelector('#notes').ej2_instances[0].value;
            var addObj = {};
            addObj.Id = scheduleObj.getEventMaxID();
            addObj.Subject = ej.base.isNullOrUndefined(subject) ? 'Add title' : subject;
            addObj.StartTime = new Date(+cellDetails.startTime);
            addObj.EndTime = new Date(+cellDetails.endTime);
            addObj.IsAllDay = cellDetails.isAllDay;
            addObj.Description = ej.base.isNullOrUndefined(notes) ? 'Add notes' : notes;
            addObj.RoomId = quickPopup.querySelector('#eventType').ej2_instances[0].value;
            return addObj;
        };
        var eventDetails;
        var currentAction;
        if (e.target.id === 'add') {
            var addObj = getSlotData();
            scheduleObj.addEvent(addObj);
        } else if (e.target.id === 'delete') {
            eventDetails = scheduleObj.activeEventData.event;
            if (eventDetails.RecurrenceRule) {
                currentAction = 'DeleteOccurrence';
            }
            scheduleObj.deleteEvent(eventDetails, currentAction);
        } else {
            var isCellPopup = quickPopup.firstElementChild.classList.contains('e-cell-popup');
            eventDetails = isCellPopup ? getSlotData() : scheduleObj.activeEventData.event;
            currentAction = isCellPopup ? 'Add' : 'Save';
            if (eventDetails.RecurrenceRule) {
                currentAction = 'EditOccurrence';
            }
            scheduleObj.openEditor(eventDetails, currentAction, true);
        }
        scheduleObj.closeQuickInfoPopup();
    };
    var HOST = new URL(window.location).origin;
    var roomData = [
        { Name: 'Jammy', Id: 1, Capacity: 20, Color: '#ea7a57', Type: 'Conference' },
        { Name: 'Tweety', Id: 2, Capacity: 7, Color: '#7fa900', Type: 'Cabin' },
        { Name: 'Nestle', Id: 3, Capacity: 5, Color: '#5978ee', Type: 'Cabin' },
        { Name: 'Phoenix', Id: 4, Capacity: 15, Color: '#fec200', Type: 'Conference' },
        { Name: 'Mission', Id: 5, Capacity: 25, Color: '#df5286', Type: 'Conference' },
        { Name: 'Hangout', Id: 6, Capacity: 10, Color: '#00bdae', Type: 'Cabin' },
        { Name: 'Rick Roll', Id: 7, Capacity: 20, Color: '#865fcf', Type: 'Conference' },
        { Name: 'Rainbow', Id: 8, Capacity: 8, Color: '#1aaa55', Type: 'Cabin' },
        { Name: 'Swarm', Id: 9, Capacity: 30, Color: '#df5286', Type: 'Conference' },
        { Name: 'Photogenic', Id: 10, Capacity: 25, Color: '#710193', Type: 'Conference' }
    ];

    var scheduleObj = new ej.schedule.Schedule({
        width: '100%',
        height: 'auto',
        // width: '300px',
        // height: '440px',
        selectedDate: new Date(2020, 0, 9),
        currentView: 'Month',
        eventSettings: {
            dataSource: ej.base.extend([], window.generateEvents(), null, true)
        },
        resources: [{
            field: 'RoomId', title: 'Room Type', name: 'MeetingRoom', textField: 'Name', idField: 'Id',
            colorField: 'Color', dataSource: ej.base.extend([], roomData, null, true)
        }],
        quickInfoTemplates: {
            header: '#header-template',
            content: '#content-template',
            footer: '#footer-template'
        },
        eventRendered: function (args) {
            var categoryColor = args.data.CategoryColor;
            if (!args.element || !categoryColor) {
                return;
            }
            if (scheduleObj.currentView === 'Agenda') {
                args.element.firstChild.style.borderLeftColor = categoryColor;
            } else {
                args.element.style.backgroundColor = categoryColor;
            }
        },
        popupOpen: function (args) {
            if (args.type === 'QuickInfo') {
                var titleObj = new ej.inputs.TextBox({ placeholder: 'Title' });
                titleObj.appendTo(args.element.querySelector('#title'));
                var typeObj = new ej.dropdowns.DropDownList({
                    dataSource: ej.base.extend([], roomData, null, true),
                    placeholder: 'Choose Type',
                    fields: { text: 'Name', value: 'Id' },
                    index: 0
                });
                typeObj.appendTo(args.element.querySelector('#eventType'));
                var notesObj = new ej.inputs.TextBox({ placeholder: 'Notes' });
                notesObj.appendTo(args.element.querySelector('#notes'));

                var moreDetailsBtn = args.element.querySelector('#more-details');
                if (moreDetailsBtn) {
                    var moreObj = new ej.buttons.Button({
                        content: 'More Details', cssClass: 'e-flat',
                        isPrimary: args.element.firstElementChild.classList.contains('e-event-popup')
                    });
                    moreObj.appendTo(moreDetailsBtn);
                    moreDetailsBtn.onclick = function (e) { buttonClickActions(e); };
                }
                var addBtn = args.element.querySelector('#add');
                if (addBtn) {
                    new ej.buttons.Button({ content: 'Add', cssClass: 'e-flat', isPrimary: true }, addBtn);
                    addBtn.onclick = function (e) { buttonClickActions(e); };
                }
                var deleteBtn = args.element.querySelector('#delete');
                if (deleteBtn) {
                    new ej.buttons.Button({ content: 'Delete', cssClass: 'e-flat' }, deleteBtn);
                    deleteBtn.onclick = function (e) { buttonClickActions(e); };
                }
            }
        }
    });
    scheduleObj.appendTo('#Schedule');

function generateEvents(count = 250, date = new Date()) {
    let names = [
        'Bering Sea Gold', 'Technology', 'Maintenance', 'Meeting', 'Travelling', 'Annual Conference', 'Birthday Celebration',
        'Farewell Celebration', 'Wedding Anniversary', 'Alaska: The Last Frontier', 'Deadliest Catch', 'Sports Day',
        'MoonShiners', 'Close Encounters', 'HighWay Thru Hell', 'Daily Planet', 'Cash Cab', 'Basketball Practice',
        'Rugby Match', 'Guitar Class', 'Music Lessons', 'Doctor checkup', 'Brazil - Mexico', 'Opening ceremony', 'Final presentation'
    ];
    let colors = [
        '#ff8787', '#9775fa', '#748ffc', '#3bc9db', '#69db7c',
        '#fdd835', '#748ffc', '#9775fa', '#df5286', '#7fa900',
        '#fec200', '#5978ee', '#00bdae', '#ea80fc'
    ];
    let startDate = new Date(date.getFullYear() - 2, 0, 1);
    let endDate = new Date(date.getFullYear() + 2, 11, 31);
    let dateCollections = [];
    for (let a = 0, id= 1; a < count; a++) {
        let start = new Date(Math.random() * (endDate.getTime() - startDate.getTime()) + startDate.getTime());
        let end = new Date(new Date(start.getTime()).setHours(start.getHours() + 1));
        let nCount = Math.floor(Math.random() * names.length);
        let n = Math.floor(Math.random() * colors.length);
        dateCollections.push({
            RoomId: a % 10,
            Id: id,
            Subject: names[nCount],
            StartTime: new Date(start.getTime()),
            EndTime: new Date(end.getTime()),
            IsAllDay: (id % 10) ? true : false,
            EventColor: colors[n],
            TaskId: (id % 5) + 1
        });
        id++;
    }
    return dateCollections;
}














































/*Day.getMonthCellText = Week.getMonthCellText = Month.getMonthCellText = TimelineViews.getMonthCellText = function (date) {
    if (date.getMonth() === 10 && date.getDate() === 23) {
        return '<img src= "https://ej2.syncfusion.com/demos/src/schedule/images/birthday.svg" />';
    } else if (date.getMonth() === 11 && date.getDate() === 9) {
        return '<img src= "https://ej2.syncfusion.com/demos/src/schedule/images/get-together.svg" />';
    } else if (date.getMonth() === 11 && date.getDate() === 13) {
        return '<img src= "https://ej2.syncfusion.com/demos/src/schedule/images/birthday.svg" />';
    } else if (date.getMonth() === 11 && date.getDate() === 22) {
        return '<img src= "https://ej2.syncfusion.com/demos/src/schedule/images/thanksgiving-day.svg" />';
    } else if (date.getMonth() === 11 && date.getDate() === 24) {
        return '<img src="https://ej2.syncfusion.com/demos/src/schedule/images/christmas-eve.svg" />';
    } else if (date.getMonth() === 11 && date.getDate() === 25) {
        return '<img src= "https://ej2.syncfusion.com/demos/src/schedule/images/christmas.svg" />';
    } else if (date.getMonth() === 0 && date.getDate() === 1) {
        return '<img src= "https://ej2.syncfusion.com/demos/src/schedule/images/newyear.svg" />';
    } else if (date.getMonth() === 0 && date.getDate() === 14) {
        return '<img src= "https://ej2.syncfusion.com/demos/src/schedule/images/birthday.svg" />';
    }
    return '';
};*/
/*window.TemplateFunction = function (date) {
    var weekEnds = [0, 6];
    if (weekEnds.indexOf(date.getDay()) >= 0) {
        return "<img src='https://ej2.syncfusion.com/demos/src/schedule/images/newyear.svg' />";
    }
    return '';
};

var TemplateFunction = {
    getWorkCellText: function(date) {
        var weekEnds = [0, 6];
        if (weekEnds.indexOf(date.getDay()) >= 0) {
            return "<img src='https://ej2.syncfusion.com/demos/src/schedule/images/newyear.svg' />";
        }
        return '';
    },
    getMonthCellText: function(date) {
        if (date.getMonth() === 10 && date.getDate() === 23) {
            return "<img src='https://ej2.syncfusion.com/demos/src/schedule/images/birthday.svg' />";
        } else if (date.getMonth() === 11 && date.getDate() === 9) {
            return "<img src='https://ej2.syncfusion.com/demos/src/schedule/images/get-together.svg' />";
        } else if (date.getMonth() === 11 && date.getDate() === 13) {
            return "<img src='https://ej2.syncfusion.com/demos/src/schedule/images/birthday.svg' />";
        } else if (date.getMonth() === 11 && date.getDate() === 22) {
            return "<img src='https://ej2.syncfusion.com/demos/src/schedule/images/thanksgiving-day.svg' />";
        } else if (date.getMonth() === 11 && date.getDate() === 24) {
            return "<img src='https://ej2.syncfusion.com/demos/src/schedule/images/christmas-eve.svg' />";
        } else if (date.getMonth() === 11 && date.getDate() === 25) {
            return "<img src='https://ej2.syncfusion.com/demos/src/schedule/images/christmas.svg' />";
        } else if (date.getMonth() === 0 && date.getDate() === 1) {
            return "<img src='https://ej2.syncfusion.com/demos/src/schedule/images/newyear.svg' />";
        } else if (date.getMonth() === 0 && date.getDate() === 14) {
            return "<img src='https://ej2.syncfusion.com/demos/src/schedule/images/birthday.svg' />";
        }
        return '';
    }
};
*/
// To apply this function to the Schedule control, you can use the following code:
/*$('#Schedule').ejSchedule({
    width: "100%",
    height: "500px",
    views: ["Day", "Week", "WorkWeek", "Month"],
    workHours: {
        highlight: false
    },
    cssClass: 'schedule-cell-template',
    cellTemplate: '${if(type === "workCells")}<div class="templatewrap">${getWorkCellText(data.date)}</div>${/if}${if(type === "monthCells")}<div class="templatewrap">${getMonthCellText(data.date)}</div>${/if}',
    selectedDate: new Date(2022, 2, 16),
    timeScale: {
        enable: true,
        interval: 60,
        slotCount: 2
    },
    showQuickInfo: false,
    eventSettings: {
        dataSource: [
            {
                Id: 1,
                Subject: "Meeting",
                StartTime: new Date(2022, 2, 3, 10, 0),
                EndTime: new Date(2022, 2, 3, 12, 0),
                IsAllDay: false
            }
        ]
    },
    eventRendered: function (args) {
        $(args.element).find('.e-cell-content').html(window.TemplateFunction(args.data.StartTime));
    }
});*/

/*$("#schedule2").ej2Schedule({
    views: ["Day", "Week", "Month", "Timeline"],
    currentView: "Month",
    height: "550px",
    monthCellRender: function(args) {
        var date = args.date;
        if (date.getDate() === 23) {
            args.element.append('<img src="https://ej2.syncfusion.com/demos/src/schedule/images/birthday.svg"/>');
        } else if (date.getDate() === 9) {
            args.element.append('<img src="https://ej2.syncfusion.com/demos/src/schedule/images/get-together.svg"/>');
        } else if (date.getDate() === 13) {
            args.element.append('<img src="https://ej2.syncfusion.com/demos/src/schedule/images/birthday.svg"/>');
        } else if (date.getDate() === 22) {
            args.element.append('<img src="https://ej2.syncfusion.com/demos/src/schedule/images/thanksgiving-day.svg"/>');
        } else if (date.getDate() === 24) {
            args.element.append('<img src="https://ej2.syncfusion.com/demos/src/schedule/images/christmas-eve.svg"/>');
        } else if (date.getDate() === 25) {
            args.element.append('<img src="https://ej2.syncfusion.com/demos/src/schedule/images/christmas.svg"/>');
        } else if (date.getDate() === 1) {
            args.element.append('<img src="https://ej2.syncfusion.com/demos/src/schedule/images/newyear.svg"/>');
        } else if (date.getDate() === 14) {
            args.element.append('<img src="https://ej2.syncfusion.com/demos/src/schedule/images/birthday.svg"/>');
        }
    }
});
*/
/*Schedule.Inject(Day, Week, Month, TimelineViews);
(window as TemplateFunction).getMonthCellText = (date: Date) => {
    if (date.getMonth() === 10 && date.getDate() === 23) {
        return '<img src= "https://ej2.syncfusion.com/demos/src/schedule/images/birthday.svg" />';
    } else if (date.getMonth() === 11 && date.getDate() === 9) {
        return '<img src= "https://ej2.syncfusion.com/demos/src/schedule/images/get-together.svg" />';
    } else if (date.getMonth() === 11 && date.getDate() === 13) {
        return '<img src= "https://ej2.syncfusion.com/demos/src/schedule/images/birthday.svg" />';
    } else if (date.getMonth() === 11 && date.getDate() === 22) {
        return '<img src= "https://ej2.syncfusion.com/demos/src/schedule/images/thanksgiving-day.svg" />';
    } else if (date.getMonth() === 11 && date.getDate() === 24) {
        return '<img src="https://ej2.syncfusion.com/demos/src/schedule/images/christmas-eve.svg" />';
    } else if (date.getMonth() === 11 && date.getDate() === 25) {
        return '<img src= "https://ej2.syncfusion.com/demos/src/schedule/images/christmas.svg" />';
    } else if (date.getMonth() === 0 && date.getDate() === 1) {
        return '<img src= "https://ej2.syncfusion.com/demos/src/schedule/images/newyear.svg" />';
    } else if (date.getMonth() === 0 && date.getDate() === 14) {
        return '<img src= "https://ej2.syncfusion.com/demos/src/schedule/images/birthday.svg" />';
    }
    return '';
};
(window as TemplateFunction).getWorkCellText = (date: Date) => {
    let weekEnds: number[] = [0, 6];
    if (weekEnds.indexOf(date.getDay()) >= 0) {
        return "<img src='https://ej2.syncfusion.com/demos/src/schedule/images/newyear.svg' />";
    }
    return '';
};

interface TemplateFunction extends Window {
    getWorkCellText?: Function;
    getMonthCellText?: Function;
}
let scheduleObj: Schedule = new Schedule({
        width: '100%',
        height: '550px',
        views: ['Day','Week', 'TimelineWeek', 'Month'],
        cssClass: 'schedule-cell-template',
        cellTemplate: '${if(type === "workCells")}<div class="templatewrap">${getWorkCellText(data.date)}</div>${/if}${if(type === "monthCells")}<div class="templatewrap">${getMonthCellText(data.date)}</div>${/if}',
        selectedDate: new Date(2017, 11, 16)
});
scheduleObj.appendTo('#Schedule');

*/