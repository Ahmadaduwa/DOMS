$(document).ready(function () {
    // *ดึงเอกสารสถานะ (แสดงผล)
    $.ajax({
        url: "./../php_code/fetch_status_documents.php",
        type: "GET",
        success: function (data) {
            $("#statusDocs").html(data);
            checkDocuments("#statusDocs");
        },
    });

    // *ดึงเอกสารโครงร่าง (แสดงผล)
    $.ajax({
        url: "./../php_code/fetch_outline_documents.php",
        type: "GET",
        success: function (data) {
            $("#draftDocs").html(data);
            checkDocuments("#draftDocs");
        },
    });

    // *ดึงเอกสารที่ถูกส่งกลับ (แสดงผล)
    $.ajax({
        url: "./../php_code/fetch_returned_documents.php",
        type: "GET",
        success: function (data) {
            $("#returnedDocs").html(data);
            checkDocuments("#returnedDocs");
        },
    });

    // *ดึงเอกสารที่อนุมัติ (แสดงผล)
    $.ajax({
        url: "./../php_code/fetch_approval_documents.php",
        type: "GET",
        success: function (data) {
            $("#approvalDocs").html(data);
            checkDocuments("#approvalDocs");
        },
    });

    // *ปุ่ม View More
    $(document).on("click", ".view-more-btn", function () {
        var buttonText = $(this).text();
        var newText = buttonText === "ดูเพิ่มเติม" ? "ดูน้อยลง" : "ดูเพิ่มเติม";
        $(this).text(newText);
        $(this).siblings(".additional-info, .card-comments").slideToggle();
    });

    // *ฟังก์ชันสำหรับการคลิกปุ่มลบ
    $(document).on("click", ".delete-btn", function () {
        var card = $(this).closest(".card");
        var id = card.data("id");

        Swal.fire({
            title: "คุณแน่ใจหรือไม่?",
            text: "คุณจะไม่สามารถเรียกคืนได้!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "ใช่, ลบเลย!",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "./php/delete_document.php",
                    type: "POST",
                    data: { id: id },
                    success: function (response) {
                        var responseData = JSON.parse(response);
                        if (responseData.status === "success") {
                            card.remove();
                            checkDocuments("#submittedDocs"); // อัพเดทหลังจากการลบ
                            Swal.fire("ลบแล้ว!", "เอกสารของคุณถูกลบแล้ว.", "success");
                        } else {
                            Swal.fire("Error!", responseData.message, "error");
                        }
                        location.reload();
                    },
                });
            }
        });
    });

    // *Function to check if there are no documents and show "No documents found." message (กรณีไม่พบข้อมูล)
    function checkDocuments(container) {
        if ($(container + " .card").length === 0) {
            $(container).html(
                '<div class="alert alert-warning" role="alert">No documents found.</div>'
            );
        }
    }

    // *ฟังก์ชันเปิด modal และเตรียมข้อมูลเดิมในการแก้ไข
    $(document).on("click", ".edit-btn", function () {
        var card = $(this).closest(".card");
        var id = card.data("id");
        var title = card.find(".card-title").text();
        var startDate = card
            .find(".additional-info p")
            .eq(0)
            .text()
            .split(": ")[1]
            .split(" To")[0];
        var endDate = card.find(".additional-info p").eq(0).text().split("To: ")[1];
        var academicYear = card
            .find(".additional-info p")
            .eq(1)
            .text()
            .split(": ")[1]
            .split(" Term")[0];
        var term = card.find(".additional-info p").eq(1).text().split("Term: ")[1];
        var description = card
            .find(".additional-info p")
            .eq(2)
            .text()
            .split(": ")[1];
        var capacity = card
            .find(".additional-info p")
            .eq(3)
            .text()
            .split(": ")[1]
            .split(" ")[0];
        var responsible = card
            .find(".additional-info p")
            .eq(4)
            .text()
            .split(": ")[1]
            .split(" Phone")[0];
        var phone = card
            .find(".additional-info p")
            .eq(4)
            .text()
            .split("Phone: ")[1];

        $("#editId").val(id);
        $("#editTitle").val(title);
        $("#editStartDate").val(startDate);
        $("#editEndDate").val(endDate);
        $("#editAcademicYear").val(academicYear);
        $("#editTerm").val(term);
        $("#editDescription").val(description);
        $("#editCapacity").val(capacity);
        $("#editResponsible").val(responsible);
        $("#editPhone").val(phone);

        $("#editModal").modal("show");
    });

    // ปุ่มออกจากระบบ
    $(".logoutBtn").click(function (event) {
        event.preventDefault();
        // ใช้ SweetAlert เพื่อแสดงข้อความยืนยัน
        Swal.fire({
            title: "คุณแน่ใจหรือไม่?",
            text: "คุณต้องการออกจากระบบหรือไม่?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "ใช่, ออก",
        }).then((result) => {
            // หากผู้ใช้กดตกลง
            if (result.isConfirmed) {
                // ทำการล็อคเอาท์
                window.location.href = "./../php_code/logout.php";
            }
        });
    });

    // ปุ่มยกเลิก
    $(document).on("click", ".cancel-btn", function () {
        var documentId = $(this).closest(".card").data("id");
        console.log("Document ID:", documentId); // ตรวจสอบว่า documentId ถูกต้องหรือไม่

        Swal.fire({
            title: "คุณแน่ใจหรือไม่?",
            text: "คุณแน่ใจหรือไม่ว่าไม่ต้องการยืนโครงการนี้?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "ยืนยัน",
            cancelButtonText: "ยกเลิก",
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("./../php_code/cancel_document.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ id: documentId }),
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            Swal.fire(
                                "Success",
                                "ยกเลิกโครงการเรียบร้อยแล้ว",
                                "success"
                            ).then(() => {
                                location.reload(); // Reload the page to see the changes
                            });
                        } else {
                            Swal.fire("Error", "เกิดข้อผิดพลาดในการยกเลิกโครงการ", "error");
                        }
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                        Swal.fire("Error", "เกิดข้อผิดพลาดในการยกเลิกโครงการ", "error");
                    });
            }
        });
    });

    // ปุ่มส่ง
    $(document).on("click", ".submit-btn", function () {
        var documentId = $(this).closest(".card").data("id");
        console.log("Document ID:", documentId); // ตรวจสอบว่า documentId ถูกต้องหรือไม่

        Swal.fire({
            title: "คุณแน่ใจหรือไม่?",
            text: "คุณแน่ใจหรือไม่ว่าต้องการยืนโครงการนี้?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "ยืนยัน",
            cancelButtonText: "ยกเลิก",
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("./../php_code/submit_document.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ id: documentId }),
                })
                    .then((response) => response.json())
                    .then((data) => {
                        console.log("Response data:", data); // ดูข้อมูลที่ได้รับจากเซิร์ฟเวอร์
                        if (data.success) {
                            Swal.fire(
                                "Success",
                                "โครงการถูกยืนเรียบร้อยแล้ว",
                                "success"
                            ).then(() => {
                                location.reload(); // Reload the page to see the changes
                            });
                        } else {
                            Swal.fire("Error", "เกิดข้อผิดพลาดในการยืนโครงการ", "error");
                        }
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                        Swal.fire("Error", "เกิดข้อผิดพลาดในการยืนโครงการ", "error");
                    });
            }
        });
    });
});