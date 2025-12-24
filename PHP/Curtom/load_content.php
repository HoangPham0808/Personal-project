<?php
$selectedService = isset($_POST['service']) ? $_POST['service'] : '';
if ($selectedService === 'giatGau') {
    echo '<table style="width: 800px; margin: 20px;justify-self: center;">
                <tr>
                    <td>
                        <img style="height: 230px;" src="icon/giatgau.png" alt="">
                    </td>
                    <td>
                        <div style="padding: 58px;background-color:rgb(251, 182, 211);border-radius: 0px 30px 30px 0px;">
                            <p style="color: #ff3f8f;">Việc tắm giặt cho các em ý là điều hoàn toàn đơn giản. Bạn chỉ cần cho em đó vào một chiếc vỏ gối hay một chiếc túi vải, cuốn chặt lại, sau đó cho vào máy giặt. Sau khi giặt xong, bạn lấy sấy khô hoặc phơi dưới nắng to để đảm bảo em gấu được thơm mùi nắng nhé!</p>
                        </div>
                    </td>
                </tr>
            </table>';
} elseif ($selectedService === 'bocQua') {
    echo '<table style="width: 800px; margin: 20px;justify-self: center;">
                <tr>
                    <td>
                        <img style="height: 230px;" src="icon/tangthiep.png" alt="">
                    </td>
                    <td>
                        <div style="padding: 38px;background-color:rgb(251, 182, 211);border-radius: 0px 30px 30px 0px;">
                            <p style="color: #ff3f8f;">Baby Three Store hiểu rằng mỗi món quà đều mang những thông điệp yêu thương và ý nghĩa riêng. Và những tấm thiệp nhỏ xinh sẽ góp phần giúp cho món quà của bạn trở nên ý nghĩa, đặc biệt và trọn vẹn hơn. Chính vì thế,  chúng tôi dành tặng bạn chiếc thiệp xinh xắn và tinh tế giúp bạn gửi gắm những lời nhắn nhủ chân thành, cảm xúc yêu thương tới những người thân yêu.</p>
                        </div>
                    </td>
                </tr>
            </table></p>';
} else {
    echo '<p>Vui lòng chọn một dịch vụ.</p>';
}
?>