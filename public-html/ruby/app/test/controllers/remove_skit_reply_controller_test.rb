require 'test_helper'

class RemoveSkitReplyControllerTest < ActionDispatch::IntegrationTest
  test "should get result" do
    get remove_skit_reply_result_url
    assert_response :success
  end

end
