require 'test_helper'

class AddSkitReplyControllerTest < ActionDispatch::IntegrationTest
  test "should get result" do
    get add_skit_reply_result_url
    assert_response :success
  end

end
